<?php

namespace Captioning\Format;

use Captioning\File;

/**
 * #strictModeException - Throw an exception when in strict mode, if strict mode is implemented
 *
 * Specification: http://www.tcax.org/docs/ass-specs.htm
 */

class SubstationalphaFile extends File
{

    const SCRIPT_TYPE_V4 = 'v4.00';
    const SCRIPT_TYPE_V4_PLUS = 'v4.00+';

    const STYLES_V4 = 'V4';
    const STYLES_V4_PLUS = 'V4+';

    protected $headers;
    protected $stylesVersion;
    protected $styles;
    protected $excludedStyles;
    protected $events;
    protected $comments;

    protected $eventsFormat = [];
    protected $stylesFormat = [];

    public function __construct($_filename = null, $_encoding = null, $_useIconv = false)
    {
        $this->headers = array(
            'Title' => '<untitled>',
            'Original Script' => '<unknown>',
            'Original Translation' => null,
            'Original Editing' => null,
            'Original Timing' => null,
            'Synch Point' => null,
            'Script Updated By' => null,
            'Update Details' => null,
            'ScriptType' => null,
            'Collisions' => 'Normal',
            'PlayResX' => 384,
            'PlayResY' => 288,
            'PlayDepth' => 0,
            'Timer' => '100.0',
            'WrapStyle' => 0
        );

        $this->stylesVersion = self::STYLES_V4_PLUS;

        $this->styles = array(
            'Name' => 'Default',
            'Fontname' => 'Arial',
            'Fontsize' => 20,
            'PrimaryColour' => '&H00FFFFFF',
            'SecondaryColour' => '&H00000000',
            'TertiaryColour' => '&0000000',
            'OutlineColour' => '&H00000000',
            'BackColour' => '&H00000000',
            'Bold' => 0,
            'Italic' => 0,
            'Underline' => 0,
            'StrikeOut' => 0,
            'ScaleX' => 100,
            'ScaleY' => 100,
            'Spacing' => 0,
            'Angle' => 0,
            'BorderStyle' => 1,
            'Outline' => 2,
            'Shadow' => 0,
            'Alignment' => 2,
            'MarginL' => 15,
            'MarginR' => 15,
            'MarginV' => 15,
            'AlphaLevel' => 0,
            'Encoding' => 0
        );

        $this->excludedStyles = array(
            self::STYLES_V4 => array('OutlineColour', 'Underline', 'StrikeOut', 'ScaleX', 'ScaleY', 'Spacing', 'Angle'),
            self::STYLES_V4_PLUS => array('TertiaryColour', 'AlphaLevel')
        );

        $this->events = array(
            self::SCRIPT_TYPE_V4 => array('Marked', 'Start', 'End', 'Style', 'Name', 'MarginL', 'MarginR', 'MarginV', 'Effect', 'Text'),
            self::SCRIPT_TYPE_V4_PLUS => array('Layer', 'Start', 'End', 'Style', 'Name', 'MarginL', 'MarginR', 'MarginV', 'Effect', 'Text')
        );

        $this->comments = array();

        parent::__construct($_filename, $_encoding, $_useIconv);
    }

    /**
     * Set script type.
     * 
     * @param string $_value Script type, eg. 'v4.00+'
     */
    public function setScriptType($_value)
    {
        if (!in_array($_value, array(self::SCRIPT_TYPE_V4, self::SCRIPT_TYPE_V4_PLUS))) {
            throw new \InvalidArgumentException('Invalid script type');
        }

        return $this->setHeader('ScriptType', $_value);
    }

    /**
     * Return script type, eg. 'v4.00'
     * @return string
     */
    public function getScriptType()
    {
        $type = $this->getHeader('ScriptType');
        if ($type === false) {
            throw new \Exception($this->filename . ' is not a proper .ass file (empty ScriptType).');
        }

        return $type;
    }

    public function setHeader($_name, $_value)
    {
        if (array_key_exists($_name, $this->headers)) {
            $this->headers[$_name] = $_value;
        }

        return $this;
    }

    public function getHeader($_name)
    {
        return isset($this->headers[$_name]) ? $this->headers[$_name] : false;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setStylesVersion($stylesVersion)
    {
        if (!in_array($stylesVersion, array(self::STYLES_V4, self::STYLES_V4_PLUS))) {
            throw new \InvalidArgumentException('Invalid styles version');
        }

        $this->stylesVersion = $stylesVersion;

        return $this;
    }

    public function getStylesVersion()
    {
        return $this->stylesVersion;
    }

    public function setStyle($_name, $_value)
    {
        if (isset($this->styles[$_name])) {
            $this->styles[$_name] = $_value;
        }
    }

    public function getStyle($_name)
    {
        return isset($this->styles[$_name]) ? $this->styles[$_name] : false;
    }

    public function getStyles()
    {
        return $this->styles;
    }

    public function getNeededStyles()
    {
        $styles = $this->styles;

        foreach ($this->excludedStyles[$this->stylesVersion] as $styleName) {
            unset($styles[$styleName]);
        }

        return $styles;
    }

    public function setStyles($_styles)
    {
        $this->styles = $_styles;
    }

    public function setEvents($_events)
    {
        if (!empty($_events) && is_array($_events)) {
            $this->events = $_events;
        }
    }

    public function getEvents()
    {
        return $this->events;
    }

    public function addComment($_comment)
    {
        $this->comments[] = $_comment;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function getNeededEvents()
    {
        return $this->events[$this->getScriptType()];
    }

    public function parse()
    {
        $fileContentArray = $this->getFileContentAsArray();
        $currentSection = '';
        $stylesFormat = [];
        $eventsFormat = [];

        while (($line = $this->getNextValueFromArray($fileContentArray)) !== false) {
            $line = preg_replace('/^[\x{feff}-\x{ffff}]/u', '', $line);

            // Ignore an empty line (hopefuly this is safe)
            if (trim($line) == '') {
                continue;
            }

            // Match comments
            if (preg_match('#^\s+;(.*)#', $line, $matches) !== 0) {
                $this->addComment($matches[1]);
                continue;
            }

            // Match section: [Name]
            if (preg_match('#^\[(.*)\]$#', $line, $matches) !== 0) {
                $currentSection = $matches[1];
                continue;
            }

            switch ($currentSection) {
                // Empty section is not allowed
                case '':
                    throw new \Exception(sprintf('%s is not valid file (empty section for line: "%s"', $this->filename, $line));
                    break;

                // Section: Script Info
                case 'Script Info':
                    $this->parseHeader($line);
                    break;

                // Section: V4+ Styles
                case 'V4+ Styles':
                    $this->parseV4PlusStyles($line);
                    break;

                // Section: Events
                case 'Events':
                    $this->parseEvent($line);
                    break;

                // Unknown section
                default:
                    // #strictModeException - Unknown section
                    break;
            }
        }

        // Result validation
        if ($this->getScriptType() === false) {
            throw new \Exception($this->filename . ' is not a proper .ass file (empty ScriptType).');
        }

        if ($this->getCuesCount() == 0) {
            throw new \Exception($this->filename . ' is not a proper .ass file (no events).');
        }

        return $this;
    }

    /**
     * Process line in Script Info section
     *
     * @param $input Whole line to process
     */
    protected function parseHeader(string $input) {
        $tmp = explode(':', $input);
        if (count($tmp) == 2) {
            $this->setHeader(trim($tmp[0]), trim($tmp[1]));
        }
    }

    /**
     * Process line with V4+ styles
     *
     * @param $input Whole line to process
     */
    protected function parseV4PlusStyles(string $input) {
        $tmp = explode(':', $input, 2);
        if (count($tmp) != 2) {
            // #strictModeException - Might be incorrect, needs to be checked
            return;
        }

        $tmp[1] = trim($tmp[1]);
        switch ($tmp[0]) {
            case 'Format':
                if (count($this->stylesFormat) > 0) {
                    throw new \Exception(sprintf('%s is not valid file (duplicate styles format definition).', $this->filename));
                }
                $this->stylesFormat = array_map(function ($value) {
                        return trim($value);
                    }, explode(',', $tmp[1]));
                break;

            case 'Style':
                if (count($this->stylesFormat) == 0) {
                    throw new \Exception(sprintf('%s is not valid file (missing format styles before style).', $this->filename));
                }


/*
            // TO BE CONTINUED ...

            // parsing styles
            if ($line === '[V4+ Styles]') {
                foreach ($tmp2 as $s) {
                    $tmp_styles[trim($s)] = null;
                }

                // line Style: ....
                $line = $this->getNextValueFromArray($fileContentArray);
                if (substr($line, 0, 6) !== "Style:") {
                    throw new \Exception($this->filename . ' is not valid file (style line).');
                }
                $tmp2 = explode(',', substr($line, 7));
                $i = 0;
                foreach (array_keys($tmp_styles) as $s) {
                    $this->setStyle($s, trim($tmp2[$i]));
                    $i++;
                }

                break;
            }
        }
*/
                break;

            default:
                // #strictModeException - Unknown styles command
                break;
        }
    }

    /**
     * Process line in Events section
     *
     * @param $input Whole line to process
     */
    protected function parseEvent(string $input) {
        $tmp = explode(':', $input, 2);
        if (count($tmp) != 2) {
            // #strictModeException - Incorrect event format
            return;
        }

        switch ($tmp[0]) {
            case 'Format':
                $format = explode(',', $tmp[1]);
                // This is a hack to allow duplicate Format lines
                if (count($this->eventsFormat) > 0 && $format != $this->eventsFormat) {
                    throw new \Exception(sprintf('%s is not a valid file (duplicate events format definition)', $this->filename));
                }
                $this->eventsFormat = $format;
                break;
            case 'Dialogue':
                $tmp[1] = trim($tmp[1]);
                if (count($this->eventsFormat) == 0) {
                    throw new \Exception(sprintf('%s is not a valid file (events format not defined)', $this->filename));
                }
                $row = [
                    'start'   => null,
                    'end'     => null,
                    'text'    => null,
                    'layer'   => 0,
                    'style'   => 'Default',
                    'name'    => '',
                    'marginl' => '0000',
                    'marginr' => '0000',
                    'marginv'  => '0000',
                    'effect'  => '',
                ];
                $tmp = explode(',', $tmp[1], count($this->eventsFormat));
                foreach ($this->eventsFormat as $index => $entry) {
                    $row[strtolower(trim($entry))] = $tmp[$index];
                }
                if (strlen($row['text']) != 0) {
                  $this->addCue(new SubstationalphaCue(
                      $row['start'], $row['end'], $row['text'], $row['layer'], $row['style'], $row['name'], $row['marginl'], $row['marginr'], $row['marginv'], $row['effect']
                  ));
                }
                break;
            default:
                // #strictModeException - Unknown event command
                break;
        }
    }

    public function buildPart($_from, $_to)
    {
        if ($this->getHeader('ScriptType') === false) {
            throw new \Exception('Script type not set');
        }

        // headers
        $buffer = '[Script Info]' . $this->lineEnding;
        foreach ($this->comments as $comment) {
            $buffer .= '; ' . str_replace($this->lineEnding, $this->lineEnding . "; ", $comment) . $this->lineEnding;
        }
        foreach ($this->headers as $key => $value) {
            if ($value !== null) {
                $buffer .= $key . ': ' . $value . $this->lineEnding;
            }
        }
        $buffer .= $this->lineEnding;

        // styles
        $buffer .= '[' . $this->stylesVersion . ' Styles]' . $this->lineEnding;

        $styles = $this->getNeededStyles();
        $buffer .= 'Format: ' . implode(', ', array_keys($styles)) . $this->lineEnding;
        $buffer .= 'Style: ' . implode(', ', array_values($styles)) . $this->lineEnding;

        // events (= cues)
        $buffer .= $this->lineEnding;
        $events = $this->getNeededEvents();
        $buffer .= '[Events]' . $this->lineEnding;
        $buffer .= 'Format: ' . implode(', ', $events) . $this->lineEnding;

        $scriptType = $this->getScriptType();
        foreach ($this->cues as $cue) {
            $buffer .= $cue->toString($scriptType) . $this->lineEnding;
        }

        $this->fileContent = $buffer;
        return $this;
    }

}

<?php

namespace Captioning\Format;

use Captioning\File;
use Exception;

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

    /**
     * Class constructor.
     *
     * @param string|null $_filename
     * @param string|null $_encoding
     * @param bool $_useIconv
     */
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
     * @return $this
     */
    public function setScriptType(string $_value): self
    {
        if (!in_array($_value, array(self::SCRIPT_TYPE_V4, self::SCRIPT_TYPE_V4_PLUS))) {
            throw new \InvalidArgumentException('Invalid script type');
        }

        return $this->setHeader('ScriptType', $_value);
    }

    /**
     * Return script type, eg. 'v4.00'
     *
     * @return string
     * @throws Exception
     */
    public function getScriptType(): string
    {
        $type = $this->getHeader('ScriptType');
        if ($type === false) {
            throw new Exception($this->filename . ' is not a proper .ass file (empty ScriptType).');
        }

        return $type;
    }

    /**
     * Set header.
     *
     * @param string $_name
     * @param mixed $_value
     * @return $this
     */
    public function setHeader(string $_name, $_value): self
    {
        if (array_key_exists($_name, $this->headers)) {
            $this->headers[$_name] = $_value;
        }

        return $this;
    }

    /**
     * Return header value.
     *
     * @param string $_name
     * @return mixed|bool
     */
    public function getHeader(string $_name)
    {
        return isset($this->headers[$_name]) ? $this->headers[$_name] : false;
    }

    /**
     * Return headers.
     *
     * @return array<string, mixed>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Set style version.
     *
     * @param string $stylesVersion
     * @return $this
     */
    public function setStylesVersion(string $stylesVersion): self
    {
        if (!in_array($stylesVersion, array(self::STYLES_V4, self::STYLES_V4_PLUS))) {
            throw new \InvalidArgumentException('Invalid styles version');
        }

        $this->stylesVersion = $stylesVersion;

        return $this;
    }

    /**
     * Return style version.
     *
     * @return string
     */
    public function getStylesVersion(): string
    {
        return $this->stylesVersion;
    }

    /**
     * Set style.
     *
     * @param string $_name
     * @param mixed $_value
     * @return void
     */
    public function setStyle(string $_name, $_value)
    {
        if (isset($this->styles[$_name])) {
            $this->styles[$_name] = $_value;
        }
    }

    /**
     * Return style.
     *
     * @param string $_name
     * @return false|mixed
     */
    public function getStyle(string $_name)
    {
        return isset($this->styles[$_name]) ? $this->styles[$_name] : false;
    }

    /**
     * Return styles.
     *
     * @return array<string, mixed>
     */
    public function getStyles(): array
    {
        return $this->styles;
    }

    /**
     * Return needed styles.
     *
     * @return array<string, mixed>
     */
    public function getNeededStyles(): array
    {
        $styles = $this->styles;

        foreach ($this->excludedStyles[$this->stylesVersion] as $styleName) {
            unset($styles[$styleName]);
        }

        return $styles;
    }

    /**
     * Add comment.
     *
     * @param string $_comment
     * @return $this
     */
    public function addComment(string $_comment): self
    {
        $this->comments[] = $_comment;

        return $this;
    }

    /**
     * Return comments.
     *
     * @return array<string>
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    /**
     * Return needed events.
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    public function getNeededEvents(): array
    {
        return $this->events[$this->getScriptType()];
    }

    /**
     * Parse file.
     *
     * @return $this
     * @throws Exception
     */
    public function parse(): self
    {
        $fileContentArray = $this->getFileContentAsArray();
        $currentSection = '';

        while (($line = $this->getNextValueFromArray($fileContentArray)) !== false) {
            $line = preg_replace('/^[\x{feff}-\x{ffff}]/u', '', $line);

            // Ignore an empty line (hopefuly this is safe)
            if (trim($line) == '') {
                continue;
            }

            // Match comments
            if (preg_match('#^\s*;(.*)#', $line, $matches) !== 0) {
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
                    throw new Exception(sprintf('%s is not valid file (empty section for line: "%s"', $this->filename, $line));
                    break;

                // Section: Script Info
                case 'Script Info':
                    $this->parseHeader($line);
                    break;

                // Section: V4+ Styles
                case 'V4 Styles':
                    $this->setStylesVersion(self::STYLES_V4);
                    $this->parseStyles($line);
                    break;

                case 'V4+ Styles':
                    $this->setStylesVersion(self::STYLES_V4_PLUS);
                    $this->parseStyles($line);
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
        $this->getScriptType();

        if ($this->getCuesCount() === 0) {
            throw new Exception($this->filename . ' is not a proper .ass file (no events).');
        }

        return $this;
    }

    /**
     * Process line in Script Info section.
     *
     * @param string $input Whole line to process
     * @return void
     */
    protected function parseHeader(string $input) {
        $tmp = explode(':', $input);
        if (count($tmp) == 2) {
            $this->setHeader(trim($tmp[0]), trim($tmp[1]));
        }
    }

    /**
     * Process line with styles.
     *
     * @param string $input Whole line to process
     * @return void
     * @throws Exception
     */
    protected function parseStyles(string $input) {
        $tmp = explode(':', $input, 2);
        if (count($tmp) != 2) {
            // #strictModeException - Might be incorrect, needs to be checked
            return;
        }

        $tmp[1] = trim($tmp[1]);
        switch ($tmp[0]) {
            case 'Format':
                if (count($this->stylesFormat) > 0) {
                    throw new Exception(sprintf('%s is not valid file (duplicate styles format definition).', $this->filename));
                }
                $this->stylesFormat = array_map(function ($value) {
                        return trim($value);
                    }, explode(',', $tmp[1]));
                break;

            case 'Style':
                if (count($this->stylesFormat) == 0) {
                    throw new Exception(sprintf('%s is not valid file (missing format styles before style).', $this->filename));
                }

                $tmp = explode(',', $tmp[1], count($this->stylesFormat));
                foreach ($this->stylesFormat as $index => $name) {
                    $this->setStyle($name, $tmp[$index]);
                }
                break;

            default:
                // #strictModeException - Unknown styles command
                break;
        }
    }

    /**
     * Process line in Events section.
     *
     * @param string $input Whole line to process
     * @throws Exception
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
                if (count($this->eventsFormat) > 0 && $format !== $this->eventsFormat) {
                    throw new Exception(sprintf('%s is not a valid file (duplicate events format definition)', $this->filename));
                }
                $this->eventsFormat = $format;
                break;
            case 'Dialogue':
                $tmp[1] = trim($tmp[1]);
                if (count($this->eventsFormat) == 0) {
                    throw new Exception(sprintf('%s is not a valid file (events format not defined)', $this->filename));
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

    /**
     * Build part.
     *
     * @param int $_from
     * @param int $_to
     * @return $this
     * @throws Exception
     */
    public function buildPart($_from, $_to): self
    {
        $this->getScriptType();

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

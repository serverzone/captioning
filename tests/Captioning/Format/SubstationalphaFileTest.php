<?php

namespace Captioning\Format;

use Exception;

class SubstationaplphaFileTest extends \PHPUnit_Framework_TestCase
{

    public function testIfAFileV4IsParsedProperly()
    {
        $filename = __DIR__ . '/../../Fixtures/Substationalpha/ssa_v4_valid.ssa';
        $file = new SubstationalphaFile($filename);

        // header
        $this->assertEquals('v4.00', $file->getScriptType());

        // cues
        $this->assertEquals(6, $file->getCuesCount());

        // first cue
        $this->assertEquals(0, $file->getCue(0)->getStartMS());
        $this->assertEquals(20000, $file->getCue(0)->getStopMS());
        $this->assertEquals(20000.0, $file->getCue(0)->getDuration());
        $this->assertEquals("Hi, my name is Fred,\Nnice to meet you.", $file->getCue(0)->getText());

        // second cue
        $this->assertEquals(21500, $file->getCue(1)->getStartMS());
        $this->assertEquals(22500, $file->getCue(1)->getStopMS());
        $this->assertEquals(1000.0, $file->getCue(1)->getDuration());
        $this->assertEquals("Hi, I'm Bill.", $file->getCue(1)->getText());

        // third cue
        $this->assertEquals(23000, $file->getCue(2)->getStartMS());
        $this->assertEquals(25000, $file->getCue(2)->getStopMS());
        $this->assertEquals(2000.0, $file->getCue(2)->getDuration());
        $this->assertEquals("Would you like to get a coffee?", $file->getCue(2)->getText());

        // fourth cue
        $this->assertEquals(27500, $file->getCue(3)->getStartMS());
        $this->assertEquals(37500, $file->getCue(3)->getStopMS());
        $this->assertEquals(10000.0, $file->getCue(3)->getDuration());
        $this->assertEquals("Sure! I've only had one today.", $file->getCue(3)->getText());

        // fifth cue
        $this->assertEquals(40000, $file->getCue(4)->getStartMS());
        $this->assertEquals(41000, $file->getCue(4)->getStopMS());
        $this->assertEquals(1000.0, $file->getCue(4)->getDuration());
        $this->assertEquals("This is my fourth!", $file->getCue(4)->getText());

        // fifth cue
        $this->assertEquals(72500, $file->getCue(5)->getStartMS());
        $this->assertEquals(92500, $file->getCue(5)->getStopMS());
        $this->assertEquals(20000.0, $file->getCue(5)->getDuration());
        $this->assertEquals("OK, let's go.", $file->getCue(5)->getText());

        // headers
        $this->assertEquals([
            'Title' => 'Untitled',
            'Original Script' => '<unknown>',
            'Original Translation' => null,
            'Original Editing' => null,
            'Original Timing' => null,
            'Synch Point' => null,
            'Script Updated By' => null,
            'Update Details' => null,
            'ScriptType' => 'v4.00',
            'Collisions' => 'Normal',
            'PlayResX' => 384,
            'PlayResY' => 288,
            'PlayDepth' => '0',
            'Timer' => '100.0',
            'WrapStyle' => 0,
        ], $file->getHeaders());

        // style
        $this->assertSame(SubstationalphaFile::STYLES_V4, $file->getStylesVersion());
        foreach ([
                     'Name' => 'Default',
                     'Fontname' => 'Arial',
                     'Fontsize' => 20,
                     'PrimaryColour' => 16777215,
                     'SecondaryColour' => 65535,
                     'TertiaryColour' => 65535,
                     'BackColour' => -2147483640,
                     'Bold' => -1,
                     'Italic' => 0,
                     'BorderStyle' => 1,
                     'Outline' => 3,
                     'Shadow' => 0,
                     'Alignment' => 2,
                     'MarginL' => 30,
                     'MarginR' => 30,
                     'MarginV' => 30,
                     'AlphaLevel' => 0,
                     'Encoding' => 0,
                 ] as $name => $value) {
            $this->assertEquals($value, $file->getStyle($name));
        }

        // comments
        $this->assertEquals([' This is a Sub Station Alpha v4 script.'], $file->getComments());
    }

    public function testIfWeGetTheFirstV4Cue()
    {
        $filename = __DIR__ . '/../../Fixtures/Substationalpha/ssa_v4_valid.ssa';
        $file = new SubstationalphaFile($filename);

        $expectedCue = new SubstationalphaCue('0:00:00.00', '0:00:20.00', "Hi, my name is Fred,\Nnice to meet you.");

        $this->assertEquals($expectedCue, $file->getFirstCue());
    }

    public function testIfWeGetTheLastV4Cue()
    {
        $filename = __DIR__ . '/../../Fixtures/Substationalpha/ssa_v4_valid.ssa';
        $file = new SubstationalphaFile($filename);

        $expectedCue = new SubstationalphaCue('0:01:12.50', '0:01:32.50', "OK, let's go.");

        $this->assertEquals($expectedCue, $file->getLastCue());
    }

    /**
     * @expectedException Exception
     */
    public function testReadInvalidV4File()
    {
        $filename = __DIR__ . '/../../Fixtures/Substationalpha/ssa_v4_invalid.ssa';

        $file = new SubstationalphaFile($filename);
    }

    public function testIfAFileV4plusIsParsedProperly()
    {
        $filename = __DIR__ . '/../../Fixtures/Substationalpha/ass_v4plus_valid.ass';
        $file = new SubstationalphaFile($filename);

        // header
        $this->assertEquals('v4.00+', $file->getScriptType());

        // cues
        $this->assertEquals(6, $file->getCuesCount());

        // first cue
        $this->assertEquals(0, $file->getCue(0)->getStartMS());
        $this->assertEquals(20000, $file->getCue(0)->getStopMS());
        $this->assertEquals(20000.0, $file->getCue(0)->getDuration());
        $this->assertEquals("Hi, my name is Fred,\Nnice to meet you.", $file->getCue(0)->getText());

        // second cue
        $this->assertEquals(21500, $file->getCue(1)->getStartMS());
        $this->assertEquals(22500, $file->getCue(1)->getStopMS());
        $this->assertEquals(1000.0, $file->getCue(1)->getDuration());
        $this->assertEquals("Hi, I'm Bill.", $file->getCue(1)->getText());

        // third cue
        $this->assertEquals(23000, $file->getCue(2)->getStartMS());
        $this->assertEquals(25000, $file->getCue(2)->getStopMS());
        $this->assertEquals(2000.0, $file->getCue(2)->getDuration());
        $this->assertEquals("Would you like to get a coffee?", $file->getCue(2)->getText());

        // fourth cue
        $this->assertEquals(27500, $file->getCue(3)->getStartMS());
        $this->assertEquals(37500, $file->getCue(3)->getStopMS());
        $this->assertEquals(10000.0, $file->getCue(3)->getDuration());
        $this->assertEquals("Sure! I've only had one today.", $file->getCue(3)->getText());

        // fifth cue
        $this->assertEquals(40000, $file->getCue(4)->getStartMS());
        $this->assertEquals(41000, $file->getCue(4)->getStopMS());
        $this->assertEquals(1000.0, $file->getCue(4)->getDuration());
        $this->assertEquals("This is my fourth!", $file->getCue(4)->getText());

        // fifth cue
        $this->assertEquals(72500, $file->getCue(5)->getStartMS());
        $this->assertEquals(92500, $file->getCue(5)->getStopMS());
        $this->assertEquals(20000.0, $file->getCue(5)->getDuration());
        $this->assertEquals("OK, let's go.", $file->getCue(5)->getText());

        // style
        $this->assertSame(SubstationalphaFile::STYLES_V4_PLUS, $file->getStylesVersion());
        $this->assertEquals([
            'Name' => 'Aoi Hana OP',
            'Fontname' => 'Magic:the Gathering',
            'Fontsize' => '79',
            'PrimaryColour' => '&H00FAEFF2',
            'SecondaryColour' => '&H000019FF',
            'TertiaryColour' => '&0000000',
            'OutlineColour' => '&H00DFD0A2',
            'BackColour' => '&H00D5A9F8',
            'Bold' => '0',
            'Italic' => '-1',
            'Underline' => '0',
            'StrikeOut' => '0',
            'ScaleX' => '119.351',
            'ScaleY' => '100',
            'Spacing' => '0',
            'Angle' => '0',
            'BorderStyle' => '1',
            'Outline' => '6.77824',
            'Shadow' => '0',
            'Alignment' => '8',
            'MarginL' => '30',
            'MarginR' => '30',
            'MarginV' => '56',
            'Encoding' => '1',
            'AlphaLevel' => 0,
        ], $file->getStyles());
    }

    public function testIfWeGetTheFirstV4plusCue()
    {
        $filename = __DIR__ . '/../../Fixtures/Substationalpha/ass_v4plus_valid.ass';
        $file = new SubstationalphaFile($filename);

        $expectedCue = new SubstationalphaCue('0:00:00.00', '0:00:20.00', "Hi, my name is Fred,\Nnice to meet you.");

        $this->assertEquals($expectedCue, $file->getFirstCue());
    }

    public function testIfWeGetTheLastV4plusCue()
    {
        $filename = __DIR__ . '/../../Fixtures/Substationalpha/ass_v4plus_valid.ass';
        $file = new SubstationalphaFile($filename);

        $expectedCue = new SubstationalphaCue('0:01:12.50', '0:01:32.50', "OK, let's go.");

        $this->assertEquals($expectedCue, $file->getLastCue());
    }

    public function testReadV4PlusNoStrictMode()
    {
        $filename = __DIR__ . '/../../Fixtures/Substationalpha/ass_v4plus_nostrict.ass';
        $file = new SubstationalphaFile($filename, null, false, false);

        $expectedCue = new SubstationalphaCue('0:01:12.50', '0:01:32.50', "OK, let's go.", 0);
        $this->assertSame(6, $file->getCuesCount());
        $this->assertEquals($expectedCue, $file->getLastCue());
    }

    /**
     * @expectedException Exception
     */
    public function testReadInvalidV4plusFile()
    {
        $filename = __DIR__ . '/../../Fixtures/Substationalpha/ass_v4plus_invalid.ass';

        $file = new SubstationalphaFile($filename);
    }

    public function testReadV4PlusNoStrictMode2()
    {
        $filename = __DIR__ . '/../../Fixtures/Substationalpha/ass_v4plus_valid2.ass';
        $file = new SubstationalphaFile($filename, null, false, false);

        $this->assertSame(319, $file->getCuesCount());

        $expectedCue = new SubstationalphaCue('0:23:49.57', '0:23:54.25', '{\fad(234,1)}Page 159\N\N{\fs18} Quiet Lakes and Forest Shadows', '0', 'sign_34238_338_Page_2_The_Boys_', 'Sign');
        $this->assertEquals($expectedCue, $file->getLastCue());
    }

    public function testReadV4PlusNoStrictMode3()
    {
        $filename = __DIR__ . '/../../Fixtures/Substationalpha/00242771a8cc5c26cfe8e3d6e26df2a9-jpn';
        $file = new SubstationalphaFile($filename, null, false, false);

        $this->assertSame(372, $file->getCuesCount());

        $expectedCue = new SubstationalphaCue('0:00:41.40', '0:00:42.50', '—おい！\N—ひっ！', 0, 'Default', '', '0', '0', '0', '');
        $this->assertEquals($expectedCue, $file->getCue(0));
    }

    public function testReadV4PlusNoStrictMode4()
    {
        $filename = __DIR__ . '/../../Fixtures/Substationalpha/0ae89e17b74fec8a55757a0c372fab6f-unk1';
        $file = new SubstationalphaFile($filename, null, false, false);

        $this->assertSame(521, $file->getCuesCount());

        $expectedCue = new SubstationalphaCue('0:01:22.29', '0:01:23.46', "I'm all right. I'm okay.", 0, 'Default', '', '0', '0', '0', '');
        $this->assertEquals($expectedCue, $file->getCue(31));
    }

    public function testReadV4PlusDuplicateFormatDefinition()
    {
        // There are 2 same lines with Format definition
        $filename = __DIR__ . '/../../Fixtures/Substationalpha/e1c3c0be75e57406ff537f0bcb0ec735-cze';
        $file = new SubstationalphaFile($filename, null, false, false);
        $this->assertSame(4, $file->getCuesCount());
    }

    /**
     *  Set incorrect script type.
     */
    public function testSetIncorrectScriptType()
    {
        $file = new SubstationalphaFile();

        $this->setExpectedException(\InvalidArgumentException::class);
        $file->setScriptType('incorrect type');
    }

    /**
     *  Set incorrect styles version.
     */
    public function testSetIncorrectStylesVersion()
    {
        $file = new SubstationalphaFile();

        $this->setExpectedException(\InvalidArgumentException::class);
        $file->setStylesVersion('incorrect version');
    }

    /**
     * Parse empty section test.
     */
    public function testParseEmptySection()
    {
        $content = <<<CONTENT
[]
Title: empty section
CONTENT;

        $file = new SubstationalphaFile();

        $this->setExpectedException(Exception::class);
        $file->loadFromString($content);
    }

    /**
     * Parse file without script type test.
     */
    public function testParseFileWithoutScriptType()
    {
        $content = <<<CONTENT
[Script Info]
Title: Untitled
PlayDepth: 0
CONTENT;

        $file = new SubstationalphaFile();

        $this->setExpectedException(Exception::class);
        $file->loadFromString($content);
    }

    /**
     * Parse file without cue test.
     */
    public function testParseFileWithoutCue()
    {
        $content = <<<CONTENT
[Script Info]
Title: Untitled
ScriptType: v4.00
PlayDepth: 0

[Events]
Format: Marked, Start, End, Style, Name, MarginL, MarginR, MarginV, Effect, Text
CONTENT;

        $file = new SubstationalphaFile();

        $this->setExpectedException(Exception::class);
        $file->loadFromString($content);
    }

    /**
     * Parse style with double format line test.
     */
    public function testParseStyleWithDoubleFormat()
    {
        $content = <<<CONTENT
[Script Info]
Title: Untitled
ScriptType: v4.00

[V4 Styles]
Format: Name, Fontname, Fontsize, PrimaryColour, SecondaryColour, TertiaryColour, BackColour, Bold, Italic, BorderStyle, Outline, Shadow, Alignment, MarginL, MarginR, MarginV, AlphaLevel, Encoding
Format: Name, Fontname, Fontsize, PrimaryColour, SecondaryColour, TertiaryColour, BackColour, Bold, Italic, BorderStyle, Outline, Shadow, Alignment, MarginL, MarginR, MarginV, AlphaLevel, Encoding
CONTENT;

        $file = new SubstationalphaFile();

        $this->setExpectedException(Exception::class);
        $file->loadFromString($content);
    }

    /**
     * Parse style before format line test.
     */
    public function testParseStyleBeforeFormat()
    {
        $content = <<<CONTENT
[Script Info]
Title: Untitled
ScriptType: v4.00

[V4 Styles]
Style: Default,Arial,20,16777215,65535,65535,-2147483640,-1,0,1,3,0,2,30,30,30,0,0
Format: Name, Fontname, Fontsize, PrimaryColour, SecondaryColour, TertiaryColour, BackColour, Bold, Italic, BorderStyle, Outline, Shadow, Alignment, MarginL, MarginR, MarginV, AlphaLevel, Encoding
CONTENT;

        $file = new SubstationalphaFile();

        $this->setExpectedException(Exception::class);
        $file->loadFromString($content);
    }

    /**
     * Parse dialogue before format line test.
     */
    public function testParseDialogueBeforeFormat()
    {
        $content = <<<CONTENT
[Script Info]
Title: Untitled
ScriptType: v4.00

[Events]
Dialogue: Marked=0,0:00:00.00,0:00:20.00,Default,,0000,0000,0000,,Hi, my name is John.
Format: Marked, Start, End, Style, Name, MarginL, MarginR, MarginV, Effect, Text
CONTENT;

        $file = new SubstationalphaFile();

        $this->setExpectedException(Exception::class);
        $file->loadFromString($content);
    }

    /**
     * Duplicate dialogue format test.
     */
    public function testDuplicateDialogueFormat()
    {
        $content = <<<CONTENT
[Script Info]
Title: Untitled
ScriptType: v4.00

[Events]
Format: Marked, Start, End, Style, Name, MarginL, MarginR, MarginV, Effect, Text
Format: Marked, Start, End, Name, Style, MarginL, MarginR, MarginV, Effect, Text
CONTENT;

        $file = new SubstationalphaFile();

        $this->setExpectedException(Exception::class);
        $file->loadFromString($content);
    }

}

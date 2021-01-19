<?php

namespace Captioning\Format;

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

}

<?php

class Ticket719TestCase extends PradoGenericSelenium2Test
{
	function test()
	{
		$this->url("tickets/index.php?page=Ticket719");
		$this->assertContains("Verifying Ticket 719", $this->source());

		$base="ctl0_Content_";

		$this->byId("${base}ctl2")->click();
		$this->pause(800);
		$this->assertVisible("${base}ctl0", 'Required');
		$this->assertVisible("${base}ctl1", 'Required');

		$this->byId("${base}autocomplete")->click();

		$this->keys('f');
		$this->pause(500);
		$this->assertContains('Finland', $this->source());

		$this->keys('r');
		$this->pause(500);
		$this->assertContains('French', $this->source());

		$this->keys('a');
		$this->pause(500);
		$this->assertContains('France', $this->source());

		$this->byCssSelector("#${base}autocomplete_result ul li")->click();
		$this->pause(800);
		$this->assertNotVisible("${base}ctl1");

		$this->byId("${base}textbox")->clear();
		$this->byId("${base}textbox")->value('Prado');
		// trigger onblur() event
		$this->byCssSelector('body')->click();

		$this->assertNotVisible("${base}ctl0");

		$this->byId("${base}ctl2")->click();
		$this->pause(800);
		$this->assertText("${base}Result", "TextBox Content : Prado -- Autocomplete Content :France");
	}
}

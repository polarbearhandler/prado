<?php
class ActiveDatePickerTestCase extends PradoGenericSelenium2Test
{
	function test()
	{
		$base='ctl0_Content_';
		$this->url("active-controls/index.php?page=ActiveDatePicker");
		$this->assertContains("TActiveDatePicker test", $this->source());
		$this->assertText("{$base}status", "");
		$this->assertValue("{$base}datepicker", date('m-d-Y'));
		$this->byId("{$base}increaseButton")->click();
		$this->pause(800);
		$this->assertValue("{$base}datepicker", date('m-d-Y', strtotime('+ 1 day')));
		$this->assertText("{$base}status", date('m-d-Y', strtotime('+ 1 day')));
		$this->byId("{$base}increaseButton")->click();
		$this->pause(800);
		$this->assertValue("{$base}datepicker", date('m-d-Y', strtotime('+ 2 day')));
		$this->assertText("{$base}status", date('m-d-Y', strtotime('+ 2 day')));
		$this->byId("{$base}todayButton")->click();
		$this->pause(800);
		$this->assertValue("{$base}datepicker", date('m-d-Y'));
		$this->assertText("{$base}status", date('m-d-Y'));
		$this->byId("{$base}decreaseButton")->click();
		$this->pause(800);
		$this->assertValue("{$base}datepicker", date('m-d-Y', strtotime('- 1 day')));
		$this->assertText("{$base}status", date('m-d-Y', strtotime('- 1 day')));
		$this->byId("{$base}datepicker")->click();
		$this->pause(800);
		$this->byCssSelector("input.todayButton")->click();
		$this->pause(800);
		$this->assertValue("{$base}datepicker", date('m-d-Y'));
		$this->assertText("{$base}status", date('m-d-Y'));
		$this->byCssSelector("input.nextMonthButton")->click();
		$this->pause(800);
		$this->assertValue("{$base}datepicker", date('m-d-Y', strtotime('+ 1 month')));
		$this->assertText("{$base}status", date('m-d-Y', strtotime('+1 month')));

		$this->byId("{$base}toggleButton")->click();
		$this->pause(2000);

		$this->byId("{$base}todayButton")->click();
		$this->pause(800);
		$this->assertSelected("{$base}datepicker_month", date('m'));
		$this->assertText("{$base}status", date('m-d-Y'));

		$this->byId("{$base}increaseButton")->click();
		$this->pause(800);
		$dateToCheck=strtotime('+ 1 day');
		$this->assertSelected("{$base}datepicker_month", date('m', $dateToCheck));
		$this->assertSelected("{$base}datepicker_day", date('d', $dateToCheck));
		$this->assertSelected("{$base}datepicker_year", date('Y', $dateToCheck));
		$this->assertText("{$base}status", date('m-d-Y', $dateToCheck));

		$this->byId("{$base}increaseButton")->click();
		$this->pause(800);
		$dateToCheck=strtotime('+ 2 day');
		$this->assertSelected("{$base}datepicker_month", date('m', $dateToCheck));
		$this->assertSelected("{$base}datepicker_day", date('d', $dateToCheck));
		$this->assertSelected("{$base}datepicker_year", date('Y', $dateToCheck));
		$this->assertText("{$base}status", date('m-d-Y', $dateToCheck));

		$this->byId("{$base}todayButton")->click();
		$this->pause(800);
		$dateToCheck=time();
		$this->assertSelected("{$base}datepicker_month", date('m', $dateToCheck));
		$this->assertSelected("{$base}datepicker_day", date('d', $dateToCheck));
		$this->assertSelected("{$base}datepicker_year", date('Y', $dateToCheck));
		$this->assertText("{$base}status", date('m-d-Y', $dateToCheck));

		$this->byId("{$base}decreaseButton")->click();
		$this->pause(800);
		$dateToCheck=strtotime('- 1 day');
		$this->assertSelected("{$base}datepicker_month", date('m', $dateToCheck));
		$this->assertSelected("{$base}datepicker_day", date('d', $dateToCheck));
		$this->assertSelected("{$base}datepicker_year", date('Y', $dateToCheck));
		$this->assertText("{$base}status", date('m-d-Y', $dateToCheck));

		$this->byId("{$base}datepickerbutton")->click();
		$this->pause(800);
		$this->byCssSelector("input.todayButton")->click();
		$this->pause(800);
		$dateToCheck=time();
		$this->assertSelected("{$base}datepicker_month", date('m', $dateToCheck));
		$this->assertSelected("{$base}datepicker_day", date('d', $dateToCheck));
		$this->assertSelected("{$base}datepicker_year", date('Y', $dateToCheck));
		$this->assertText("{$base}status", date('m-d-Y', $dateToCheck));

		$this->byCssSelector("input.nextMonthButton")->click();
		$this->pause(800);
		$dateToCheck=strtotime('+ 1 month');
		$this->assertSelected("{$base}datepicker_month", date('m', $dateToCheck));
		$this->assertSelected("{$base}datepicker_day", date('d', $dateToCheck));
		$this->assertSelected("{$base}datepicker_year", date('Y', $dateToCheck));
		$this->assertText("{$base}status", date('m-d-Y', $dateToCheck));

		$this->byId('ctl0_ctl1')->click();
		$this->pause(800);

		$this->assertText("{$base}status2", "");
		$dateToCheck=time();
		$this->assertSelected("{$base}datepicker2_month", date('m', $dateToCheck));
		$this->assertSelected("{$base}datepicker2_day", date('d', $dateToCheck));
		$this->assertSelected("{$base}datepicker2_year", date('Y', $dateToCheck));
		$this->select("{$base}datepicker2_year", date('Y')+1);
		$this->pause(800);
		$dateToCheck=mktime(0,0,0,(int)date('m'),(int)date('d'), date('Y')+1);
		$this->assertText("{$base}status2", date('m-d-Y', $dateToCheck));


		$this->assertText("{$base}status3", "");
		$dateToCheck=time();
		$this->assertSelected("{$base}datepicker3_month", date('F', $dateToCheck));
		$this->assertSelected("{$base}datepicker3_year", date('Y', $dateToCheck));
		$this->select("{$base}datepicker3_year", date('Y')+1);
		$this->pause(800);
		$dateToCheck=mktime(0,0,0,(int)date('m'),(int)date('d'), date('Y')+1);
		$this->assertText("{$base}status3", date('m/Y', $dateToCheck));
	}
}

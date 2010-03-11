<?php

class InvestorrelationController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction() {
		// just render view
    }

    public function turnoverAction() {
        // just render view 
    }

    public function getturnoverAction()
    {
	    $this->_helper->layout->disableLayout();
                        
		require_once 'OpenFlashChart/php-ofc-library/open-flash-chart.php';

		// Begin Chart
		$chart = new open_flash_chart(); 
		$title = new title('Umsatzzahlen 2008 zu 2009'); // define title
		$chart->set_title($title); 
		$chart->set_bg_colour('#FFFFFF'); // set the background colour

		// First bar chart
		$bar1 = new bar_glass();
		$bar1->colour('#B8BCC2');
		$bar1->key('last year', 14); // legend text with height in pixels
		$data1 = array(6,5,7,1,1,3,7,0.5,5,6,4,5); // turnover data 
		$bar1->set_values($data1);
		$bar1->set_tooltip('#val# Mio. EURO');
		$chart->add_element($bar1);

		// Second bar chart
		$bar2 = new bar_glass();
		$bar2->colour('#4583DD');
		$bar2->key('current year', 14); // legend text with height in pixels
		$data2 = array(3,3,9,5,8,3,5,2,6,4,2,2); // turnover data 
		$bar2->set_values($data2);
		$bar2->set_tooltip('#val# Mio. EURO');
		$chart->add_element($bar2);

		// x-labels
		$months=array('Jan', 'Feb', 'Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
		$x_labels = new x_axis_labels();
		$x_labels->set_steps(1); // display label on every step
		$x_labels->set_vertical();
		$x_labels->set_colour('black');
		$x_labels->set_labels($months);

		// x-axis
		$x = new x_axis();
		$x->set_colour('#A2ACBA');
		$x->set_grid_colour('#D7E4A3');
		$x->set_steps(1);
		// add X Axis Labels to  X Axis
		$x->set_labels($x_labels);
		// add x-axis to chart
		$chart->set_x_axis($x);

		// Output
		$this->view->chart = $chart->toPrettyString();
    }

    public function visitorAction() {
        // just render view 
    }

	public function getvisitorAction() {
		$this->_helper->layout->disableLayout();
                        
		require_once 'OpenFlashChart/php-ofc-library/open-flash-chart.php';

		// Begin Chart
		$chart = new open_flash_chart(); 
		$title = new title('Umsatzzahlen 2008 zu 2009'); // define title
		$chart->set_title($title); 
		$chart->set_bg_colour('#FFFFFF'); // set the background colour

		// First bar chart
		$bar1 = new bar_glass();
		$bar1->colour('#B8BCC2');
		$bar1->key('last year', 14); // legend text with height in pixels
		$data1 = array(6,5,7,1,1,3,7,0.5,5,6,4,5); // turnover data 
		$bar1->set_values($data1);
		$bar1->set_tooltip('#val# Mio. EURO');
		$chart->add_element($bar1);

		// Second bar chart
		$bar2 = new bar_glass();
		$bar2->colour('#4583DD');
		$bar2->key('current year', 14); // legend text with height in pixels
		$data2 = array(3,3,9,5,8,3,5,2,6,4,2,2); // turnover data 
		$bar2->set_values($data2);
		$bar2->set_tooltip('#val# Mio. EURO');
		$chart->add_element($bar2);

		// x-labels
		$months=array('Jan', 'Feb', 'Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
		$x_labels = new x_axis_labels();
		$x_labels->set_steps(1); // display label on every step
		$x_labels->set_vertical();
		$x_labels->set_colour('black');
		$x_labels->set_labels($months);

		// x-axis
		$x = new x_axis();
		$x->set_colour('#A2ACBA');
		$x->set_grid_colour('#D7E4A3');
		$x->set_steps(1);
		// add X Axis Labels to  X Axis
		$x->set_labels($x_labels);
		// add x-axis to chart
		$chart->set_x_axis($x);

		// Output
		$this->view->chart = $chart->toPrettyString();

    }


}










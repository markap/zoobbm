<?php

/**
 * pdf class for booking
 * @package models
 */
class Model_BookingPdf extends Model_Pdf {

	/**
	 * sets the booking details
	 *
	 * @author Martin Kapfhammer
	 * @param array $details details of the tarif
	 */
	public function setBookingDetails(array $details) {
		$this->setDate($details['date']);
		$this->page->drawText('Tarif: ' . $details['tarif'], 50, 620);
		$this->page->drawText('Erwachsene: ', 50, 600);
		$this->page->drawText($details['adult'][0], 150, 600);
		$this->page->drawText($details['adult'][1] . '€', 200, 600);
		$this->page->drawText('Kinder: ', 50, 590);
		$this->page->drawText($details['child'][0], 150, 590);
		$this->page->drawText($details['child'][1] . '€', 200, 590);
		$this->page->drawText('Ermäßigte: ', 50, 580);
		$this->page->drawText($details['student'][0], 150, 580);
		$this->page->drawText($details['student'][1] . '€', 200, 580);
		$this->page->setLineColor(new Zend_Pdf_Color_Html('black'));
		$this->page->drawLine(50, 570, 250, 570);
		$this->page->drawText('Gesamt: ', 50, 560);
		$this->page->drawText($details['sum'] . '€', 200, 560);
	}


	/**
	 * sets the date
	 * 
	 * @author Martin Kapfhammer
	 * @param array $date array contains start and enddate
	 */
	protected function setDate(array $date) {
		if ($date['enddate']) {
			$text = 'Ticket vom ' . $date['startdate'] . ' bis zum ' . $date['enddate'];
		} else {
			$text = 'Ticket für den ' . $date['startdate'];
		}	
		$this->page->drawText($text, 50, 650);
	}

	
}

<?php

/**
 * default pdf class for zoo mannheim
 * @package models
 */
class Model_Pdf {
	
	/**
	 * @var Zend_Pdf
	 */
	protected $pdf = null;

	/**
	 * @var Zend_Pdf_Page
	 */
	protected $page = null;	


	/**
	 * constructor
	 * creates a new pdf and a new page
	 * @author Martin Kapfhammer	
 	 */
	public function __construct() {
		$this->pdf = new Zend_Pdf();
		$this->page = $this->pdf->newPage(Zend_Pdf_Page::SIZE_A4);
		$this->pdf->pages[] = $this->page;
	}

	
	/**
	 * sets the Pdf - Header
	 * 
	 * @author Martin Kapfhammer
	 * @param string $title
	 */
	public function setHeader($title) {
		$this->page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 20);
		$this->page->setLineColor(new Zend_Pdf_Color_Html('green'));
		$this->page->drawLine(40, 800, 550, 800);
		$title = 'Zoo Mannheim - ' . $title;
		$this->page->drawText($title, 50, 780);
		$this->page->drawLine(40, 770, 550, 770);
	}


	/**
	 * set font size
	 * 
	 * @author Martin Kapfhammer
	 * @param integer $fontSize
	 */
	protected function setFontSize($fontSize) {
		$this->page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), $fontSize);
	}


	/**
	 * sets the userData into pdf
	 * 
	 * @author Martin Kapfhammer
	 * @param string $userId
	 * @param string $fullname
	 * @param string $username
	 */
	public function setUserData($userId, $fullname, $username) {
		$this->setFontSize(12);
		$this->page->drawText('Kundennummer: ' . $userId, 50, 700);
		$this->page->drawText('Name: ' . $fullname, 200, 700);
		$this->page->drawText('Benutzername: ' . $username, 400, 700);
	}

	/**
	 * sets footer
	 * 
	 * @author Martin kapfhammer
	 */
	public function setFooter() {
		$this->setFontSize(16);
		$this->page->drawText('Mit freundlichen Grüßen', 50, 200);
		$this->page->drawText('Ihr Zoo Mannheim', 50, 160);
	}
	

	/**
	 * sets the default paytext
	 * @author Martin Kapfhammer
	 */
	public function setPayText() {
		$this->setFontSize(12);
		$this->page->drawText('Vielen Dank für Ihre Buchung. Sie können jetzt einfach an den Zoo Mannheim PayAutomaten', 50, 400);
		$this->page->drawText('schnell und einfach bezahlen. Halten Sie dazu einfach nur diese Bestätigung unter den Scanner.', 50, 385);
		$this->page->drawText('Viel Spaß bei Ihrem Zoobesuch!', 50, 370);
	}
	

	/** 
	 * sets the ean code
	 * @author Martin Kapfhammer
 	 */
	public function setEAN() {
		$this->page->drawText('Ihr Buchungscode:' , 100, 460);
		$image = Zend_Pdf_Image::imageWithPath('img/ean.jpg');
		$this->page->drawImage($image, 50, 450, 306, 441);
	}


	/**
 	 * saves the pdf 
	 * 
	 * @author Martin Kapfhammer
	 * @param string $fileName
	 */
	public function savePdf($fileName) {
		$pathAndFileName = APPLICATION_PATH . '/../public/pdf/' . $fileName;
		$this->pdf->save($pathAndFileName);	
	}

}

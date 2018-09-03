<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx\PDF;

use Fenxweb\Fenx\Module;
use Fenxweb\Fenx\Application;
use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * class Printer.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class Printer {
    /**
     * @var Html2Pdf
     */
    private $pdf;

    public function __construct () {
        $this->pdf = new Html2Pdf('P', 'A4', 'fr');
    }

    public function configure($orientation,$format, $lang, $unicode, $margin){
        $this->pdf = new Html2Pdf($orientation,$format, $lang, $unicode, $margin);
        $this->pdf->pdf->SetDisplayMode('fullpage');
    }

    public function output($content,$name){
        $this->pdf->writeHTML($content);
        $content =  $this->pdf->pdf->Output($name);
        $response = new Response($content);

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $name
        );

        $response->headers->set('Content-Disposition', $disposition);
        return $response;
    }
}
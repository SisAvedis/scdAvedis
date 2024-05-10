<?php
require('../fpdf181/fpdf.php');
define('EURO', chr(128) );
define('EURO_VAL', 6.55957 );


//////////////////////////////////////
// Public functions                 //
//////////////////////////////////////
//  function sizeOfText( $texte, $larg )
//  function addSociete( $nom, $adresse )
//  function fact_dev( $libelle, $num )
//  function addDevis( $numdev )
//  function addFacture( $numfact )
//  function addDate( $date )
//  function addClient( $ref )
//  function addPageNumber( $page )
//  function addClientAdresse( $adresse )
//  function addOperadorAdresse( $adresse )
//  function addReglement( $mode )
//  function addEcheance( $date )
//  function addNumTVA($tva)
//  function addReference($ref)
//  function addCols( $tab )
//  function addLineFormat( $tab )
//  function lineVert( $tab )
//  function addLine( $ligne, $tab )
//  function addRemarque($remarque)
//  function addCadreTVAs()
//  function addCadreEurosFrancs()
//  function addTVAs( $params, $tab_tva, $invoice )
//  function temporaire( $texte )

class PDF_Invoice extends FPDF
{
// private variables
var $colonnes;
var $format;
var $angle=0;

// private functions
function RoundedRect($x, $y, $w, $h, $r, $style = '')
{
	$k = $this->k;
	$hp = $this->h;
	if($style=='F')
		$op='f';
	elseif($style=='FD' || $style=='DF')
		$op='B';
	else
		$op='S';
	$MyArc = 4/3 * (sqrt(2) - 1);
	$this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
	$xc = $x+$w-$r ;
	$yc = $y+$r;
	$this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

	$this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
	$xc = $x+$w-$r ;
	$yc = $y+$h-$r;
	$this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
	$this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
	$xc = $x+$r ;
	$yc = $y+$h-$r;
	$this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
	$this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
	$xc = $x+$r ;
	$yc = $y+$r;
	$this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
	$this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
	$this->_out($op);
}

function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
{
	$h = $this->h;
	$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
						$x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
}

function Rotate($angle, $x=-1, $y=-1)
{
	if($x==-1)
		$x=$this->x;
	if($y==-1)
		$y=$this->y;
	if($this->angle!=0)
		$this->_out('Q');
	$this->angle=$angle;
	if($angle!=0)
	{
		$angle*=M_PI/180;
		$c=cos($angle);
		$s=sin($angle);
		$cx=$x*$this->k;
		$cy=($this->h-$y)*$this->k;
		$this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
	}
}

function _endpage()
{
	if($this->angle!=0)
	{
		$this->angle=0;
		$this->_out('Q');
	}
	parent::_endpage();
}

// public functions
function sizeOfText( $texte, $largeur )
{
	$index    = 0;
	$nb_lines = 0;
	$loop     = TRUE;
	while ( $loop )
	{
		$pos = strpos($texte, "\n");
		if (!$pos)
		{
			$loop  = FALSE;
			$ligne = $texte;
		}
		else
		{
			$ligne  = substr( $texte, $index, $pos);
			$texte = substr( $texte, $pos+1 );
		}
		$length = floor( $this->GetStringWidth( $ligne ) );
		$res = 1 + floor( $length ) ;
		$nb_lines += $res;
	}
	return $nb_lines;
}

// Company
function addSociete( $nom, $ext_nom, $adresse,$logo,$ext_logo)
{
	$x1 = 37;
	$y1 = 9;
	//Positionnement en bas
	$this->Image($logo , 14 ,$y1, 22 , 22 , $ext_logo);
	$this->SetXY( $x1, $y1 );
	$this->SetFont('Arial','B',12);
	$length = $this->GetStringWidth( $nom );
	// $this->Cell( $length, 2, $nom);
	$this->Image($nom , 37.4 ,$y1-4, 31 , 10 , $ext_nom);
	$this->SetXY( $x1, $y1 + 7 );
	$this->SetFont('Arial','',7);
	$length = $this->GetStringWidth( $adresse );
	//Coordonn�es de la soci�t�
	$lignes = $this->sizeOfText( $adresse, $length) ;
	$this->MultiCell($length, 3.7, $adresse);
}

// Label and number of invoice/estimate
function fact_dev( $libelle, $num )
{
    $r1  = $this->w - 61;
    $r2  = $r1 + 49;
    $y1  = 6;
    $y2  = $y1 + 2;
    $mid = ($r1 + $r2 ) / 2;
    
    $texte  = $libelle  . $num;    
    $szfont = 12;
    $loop   = 0;
    
    while ( $loop == 0 )
    {
       $this->SetFont( "Arial", "B", $szfont );
       $this->SetTextColor( 255,255,255 );
       $sz = $this->GetStringWidth( $texte );
       if ( ($r1+$sz) > $r2 )
          $szfont --;
       else
          $loop ++;
    }

    $this->SetLineWidth(0.1);
    $this->SetFillColor(	11, 38, 105);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 2.5, 'DF');
    $this->SetXY( $r1+1, $y1+2);
    $this->Cell($r2-$r1 -1,5, $texte, 0, 0, "C" );
}

// Estimate
function addDevis( $numdev )
{
	$string = sprintf("DEV%04d",$numdev);
	$this->fact_dev( "Devis", $string );
}

// Invoice
function addFacture( $numfact )
{
	$string = sprintf("FA%04d",$numfact);
	$this->fact_dev( "Facture", $string );
}

function addDate( $date )
{
	$r1  = $this->w - 61;
	$r2  = $r1 + 49;
	$y1  = 17;
	$y2  = $y1 ;
	$mid = $y1 + ($y2 / 2);
	$this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 3.5, 'D');
	$this->Line( $r1, $mid, $r2, $mid);
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1+3 );
	$this->SetFont( "Arial", "B", 10);
	$this->Cell(10,5, "Fecha", 0, 0, "C");
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1+9 );
	$this->SetFont( "Arial", "", 10);
	$this->Cell(10,5,$date, 0,0, "C");
}




function addTitle( )
{
	$r1  = $this->w -197;
	$r2  = $r1+ 185 ;
	$y1  = 38;
	$y2  = $y1 -20;
	$mid = $y1 + ($y2 / 2);
	$this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 3, 'D');
	$this->Line( $r1, $mid, $r2, $mid);
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1+3 );
	$this->SetFont( "Arial", "B", 12);
	$this->Cell(10,4, "PLANILLA  DE CONTROL DE PUREZA E IMPUREZAS", 0, 0, "C");
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1 + 9 );
	$this->SetFont( "Arial", "B", 8.5);
	$this->Cell(10,9,utf8_decode("ANÁLISIS DE PUREZA E IMPUREZAS DE MONÓXIDO DE CARBONO, DIÓXIDO DE CARBONO Y HUMEDAD EN OXÍGENO"), 0,0, "C");
}

function addPageNumber( $page )
{
	$r1  = $this->w - 80;
	$r2  = $r1 + 19;
	$y1  = 17;
	$y2  = $y1;
	$mid = $y1 + ($y2 / 2);
	$this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 3.5, 'D');
	$this->Line( $r1, $mid, $r2, $mid);
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1+3 );
	$this->SetFont( "Arial", "B", 10);
	$this->Cell(10,5, "PAGE", 0, 0, "C");
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1 + 9 );
	$this->SetFont( "Arial", "", 10);
	$this->Cell(10,5,$page, 0,0, "C");
}

// Client address
function addClientAdresse( $cliente,$domicilio,$num_documento,$email,$telefono )
{
	$r1     = $this->w - 180;
	$r2     = $r1 + 68;
	$y1     = 40;
	$this->SetXY( $r1, $y1);
	$this->SetFont( "Arial", "B", 10);
	$this->MultiCell( 60, 4, "CLIENTE");
	$this->SetXY( $r1, $y1+5);
	$this->SetFont( "Arial", "", 10);
	$this->MultiCell( 150, 4, $cliente);
	$this->SetXY( $r1, $y1+10);
	$this->MultiCell( 150, 4, $domicilio);
	$this->SetXY( $r1, $y1+15);
	$this->MultiCell( 150, 4, $num_documento);
	$this->SetXY( $r1, $y1+20);
	$this->MultiCell( 150, 4, $email);
	$this->SetXY( $r1, $y1+25);
	$this->MultiCell( 150, 4, $telefono);
}

// Operador address
function addCabecera($fecha, $partida, $certificado, $cliente, $analisis, $localidad ){
	$r1     = $this->w - 192;
	$r2     = $r1 -7;
	$y1     = 38;
	$this->SetXY( $r2, $y1-2);
	// $this->MultiCell( 190, 2, '', 'T');
	$this->MultiCell( 190, 0, '', 'T');
	$this->SetXY( $r1, $y1);
	$this->SetFont( "Arial", "B", 10);
	$this->MultiCell( 190, 10, "CORRESPONDIENTE AL DIA: ".$fecha);
	// $this->SetXY( $r1+(10*6), $y1);
	// $this->MultiCell( 150, 10, "PARTIDA: ".$partida);
	// $this->SetXY( $r1+(10*12), $y1);
	// $this->MultiCell( 150, 10, "CERTIFICADO N: ".$certificado);
	// $this->SetXY( $r1, $y1+(10*1));
	// $this->MultiCell( 150, 10, "ENTIDAD: ".$cliente);
	// $this->SetXY( $r1+120, $y1+(10*1));
	// $this->MultiCell( 150, 10, "LOCALIDAD: ".$localidad);
	// $this->SetXY( $r1, $y1+(10*2));
	// $this->MultiCell( 150, 10, "Sr. Jefe de Farmacia: ");
	// $this->SetXY( $r1, $y1+(10*3));
	// $this->MultiCell( 170, 10, utf8_decode("Por medio de la presente le envío los resultados del Análisis Nº:  ".$analisis."  , cuyos"));
	// $this->SetXY( $r1, $y1+(10*3.5));
	// $this->MultiCell( 170, 10, utf8_decode("valores ponderados se encuentran dentro de lo estipulado segun la normativa vigente."));


}
function addDescriptionText( $renglon1,$renglon2,$renglon3,$renglon4,$renglon5,$renglon6)
{
	$r1     = $this->w - 192;
	$r2     = $r1 + 58;
	$y1     = 170;
	$this->SetXY( $r1, $y1);
	$this->SetFont( "Arial", "B", 10);
	$this->MultiCell( 150, 4, $renglon1);
	$this->SetXY( $r1, $y1+5);
	$this->MultiCell( 150, 4, $renglon2);
	$this->SetXY( $r1, $y1+(5*2));
	$this->MultiCell( 150, 4, $renglon3);
	$this->SetXY( $r1, $y1+(5*3));
	$this->MultiCell( 150, 4, $renglon4);
	$this->SetXY( $r1, $y1+(5*4));
	$this->MultiCell( 150, 4, $renglon5);
	$this->SetXY( $r1, $y1+(5*5));
	$this->MultiCell( 150, 4, $renglon6);
}
function addOperadorAdresse( $cliente)
{
	$r1     = $this->w - 192;
	$r2     = $r1 + 68;
	$y1     = 210;
	$this->SetXY( $r1, $y1);
	$this->SetFont( "Arial", "B", 10);
	$this->MultiCell( 60, 4, "Analista:");
	$this->SetXY( $r1+18, $y1);
	$this->SetFont( "Arial", "", 10);
	$this->MultiCell( 150, 4, $cliente);
}
function addLibroyFojas( $libro, $fojas)
{
	$r1     = $this->w - 192;
	$r2     = $r1 + 68;
	$y1     = 272;
	$this->SetXY( $r1, $y1);
	$this->SetFont( "Arial", "B", 10);
	$this->MultiCell( 60, 4, "ISOFORM -064");
	$this->SetXY( $r1+120, $y1);
	$this->SetFont( "Arial", "B", 10);
	$this->MultiCell( 60, 4, "Libro: ");
	$this->SetXY( $r1+137, $y1);
	$this->SetFont( "Arial", "", 10);
	$this->MultiCell( 150, 4, $libro);
	$this->SetXY( $r1+152, $y1);
	$this->SetFont( "Arial", "B", 10);
	$this->MultiCell( 60, 4, "Fojas: ");
	$this->SetXY( $r1+169, $y1);
	$this->SetFont( "Arial", "", 10);
	$this->MultiCell( 150, 4, $fojas);
}
function addVencimientoAdresse( $vencimiento)
{
	$r1     = $this->w - 192;
	$r2     = $r1 + 68;
	$y1     = 230;
	$this->SetXY( $r1, $y1);
	$this->SetFont( "Arial", "B", 10);
	$this->MultiCell( 60, 4, "Fecha de Vto:");
	$this->SetXY( $r1+25, $y1);
	$this->SetFont( "Arial", "", 10);
	$this->MultiCell( 150, 4, $vencimiento);
}

function addSello($sello)
{
	$r1     = $this->w - 200;
	$r2     = $r1 + 68;
	$y1     = 260;
	$this->SetXY( $r1, $y1);
	$this->SetFont( "Arial", "", 10);
	$this->MultiCell( 60, 4, $sello, 0 , "C");
	
}

// Mode of payment
function addReglement( $mode )
{
	$r1  = 10;
	$r2  = $r1 + 60;
	$y1  = 80;
	$y2  = $y1+10;
	$mid = $y1 + (($y2-$y1) / 2);
	$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
	$this->Line( $r1, $mid, $r2, $mid);
	$this->SetXY( $r1 + ($r2-$r1)/2 -5 , $y1+1 );
	$this->SetFont( "Arial", "B", 10);
	$this->Cell(10,4, "CLIENTE", 0, 0, "C");
	$this->SetXY( $r1 + ($r2-$r1)/2 -5 , $y1 + 5 );
	$this->SetFont( "Arial", "", 10);
	$this->Cell(10,5,$mode, 0,0, "C");
}

// Expiry date
function addEcheance( $documento,$numero )
{
	$r1  = 80;
	$r2  = $r1 + 40;
	$y1  = 80;
	$y2  = $y1+10;
	$mid = $y1 + (($y2-$y1) / 2);
	$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
	$this->Line( $r1, $mid, $r2, $mid);
	$this->SetXY( $r1 + ($r2 - $r1)/2 - 5 , $y1+1 );
	$this->SetFont( "Arial", "B", 10);
	$this->Cell(10,4, $numero, 0, 0, "C");
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5 , $y1 + 5 );
	$this->SetFont( "Arial", "", 10);
	$this->Cell(10,5,$numero, 0,0, "C");
}

// VAT number
function addNumTVA($tva)
{
	$this->SetFont( "Arial", "B", 10);
	$r1  = $this->w - 80;
	$r2  = $r1 + 70;
	$y1  = 80;
	$y2  = $y1+10;
	$mid = $y1 + (($y2-$y1) / 2);
	$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
	$this->Line( $r1, $mid, $r2, $mid);
	$this->SetXY( $r1 + 16 , $y1+1 );
	$this->Cell(40, 4, "DIRECCI�N", '', '', "C");
	$this->SetFont( "Arial", "", 10);
	$this->SetXY( $r1 + 16 , $y1+5 );
	$this->Cell(40, 5, $tva, '', '', "C");
}

function addReference($ref)
{
	$this->SetFont( "Arial", "", 10);
	$length = $this->GetStringWidth( "R�f�rences : " . $ref );
	$r1  = 10;
	$r2  = $r1 + $length;
	$y1  = 92;
	$y2  = $y1+5;
	$this->SetXY( $r1 , $y1 );
	$this->Cell($length,4, "R�f�rences : " . $ref);
}

function addCols( $tab, $tab2 )
{
	global $colonnes;
	
	$r1  = 9;
	$r2  = $this->w - (10 * 1.7) ;
	$y1  = 60;
	$y2  = $this->h - 27 - $y1;
	$this->SetXY( $r1, $y1 );
	$this->Rect( $r1, $y1, $r2, $y2, "D");
	$this->Line( $r1, $y1+10, $r1+$r2, $y1+10);
	$colX = $r1;
	$colonnes = $tab;
	$this->SetFont( "Arial", "B", 6.8);
	$this->SetXY( $colX + 117.1, $y1+1 );
	$this->Cell( $r1, $y1-46, "99,5%", 0, 0, "C",);
	$this->SetXY( $colX + 130, $y1+1 );
	$this->Cell( $r1, $y1-46, "< 5 ppm", 0, 0, "C",);
	$this->SetXY( $colX + 143, $y1+1 );
	$this->Cell( $r1, $y1-46, "< 300 ppm", 0, 0, "C",);
	$this->SetXY( $colX + 156, $y1+1 );
	$this->Cell( $r1, $y1-46, "< 67 ppm", 0, 0, "C",);
	while ( list( $lib, $pos ) = each($tab) )
	{
		$this->SetFont( "Arial", "B", 9);
		$this->SetXY( $colX, $y1+3 );
		
		$this->Cell( $pos, 1, $lib, 0, 0, "C",);
		$colX += $pos;
		$this->Line( $colX, $y1, $colX, $y1+$y2);
	}
	$colX = $r1;
	while ( list( $lib, $pos ) = each($tab2) )
	{
		if (strpos($lib,'/') != false){
			$this->SetFont( "Arial", "B", 6.6);
			
		}else{
			$this->SetFont( "Arial", "B", 9);

		}
		$this->SetXY( $colX, $y1+7 );
		$this->Cell( $pos, 1, $lib, 0, 0, "C",);
		$colX += $pos;
		$this->Line( $colX, $y1, $colX, $y1+$y2);
	}
	// $this->SetXY( 19.3, 143);
	// $this->MultiCell(50,4,utf8_decode("Halógenos, Ácidos, Alcalis y Sustancias Oxidantes"),0,"C");
}

function addLineFormat( $tab )
{
	global $format, $colonnes;
	
	while ( list( $lib, $pos ) = each($colonnes) )
	{
		if ( isset( $tab["$lib"] ) )
			$format[ $lib ] = $tab["$lib"];
	}
}
function MultiCellRow($cells, $width, $height, $data, $pdf)
{
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $maxheight = 0;

    for ($i = 0; $i < $cells; $i++) {
        $pdf->MultiCell($width, $height, $data[$i]);
        if ($pdf->GetY() - $y > $maxheight) $maxheight = $pdf->GetY() - $y;
        $pdf->SetXY($x + ($width * ($i + 1)), $y);
    }

    for ($i = 0; $i < $cells + 1; $i++) {
        $pdf->Line($x + $width * $i, $y, $x + $width * $i, $y + $maxheight);
    }

    $pdf->Line($x, $y, $x + $width * $cells, $y);
    $pdf->Line($x, $y + $maxheight, $x + $width * $cells, $y + $maxheight);
}
function lineVert( $tab )
{
	global $colonnes;

	reset( $colonnes );
	$maxSize=0;
	while ( list( $lib, $pos ) = each($colonnes) )
	{
		$texte = $tab[ $lib ];
		$longCell  = $pos -2;
		$size = $this->sizeOfText( $texte, $longCell );
		if ($size > $maxSize)
			$maxSize = $size;
	}
	return $maxSize;
}

// add a line to the invoice/estimate
/*    $ligne = array( "REFERENCE"    => $prod["ref"],
                      "DESIGNATION"  => $libelle,
                      "QUANTITE"     => sprintf( "%.2F", $prod["qte"]) ,
                      "P.U. HT"      => sprintf( "%.2F", $prod["px_unit"]),
                      "MONTANT H.T." => sprintf ( "%.2F", $prod["qte"] * $prod["px_unit"]) ,
                      "TVA"          => $prod["tva"] );
*/
function addLine( $ligne, $tab, $border )
{
	global $colonnes, $format;

	$ordonnee     = 9;
	$maxSize      = $ligne;

	reset( $colonnes );
	while ( list( $lib, $pos ) = each ($colonnes) )
	{
		$longCell  = $pos ;
		$texte     = $tab[ $lib ];
		$length    = $this->GetStringWidth( $texte );
		$tailleTexte = $this->sizeOfText( $texte, $length );
		$formText  = $format[ $lib ];
		$this->SetXY( $ordonnee, $ligne-0);
		$this->MultiCell( $longCell, 3 , $texte."\n ", $border, $formText);
		if ( $maxSize < ($this->GetY()  ) )
			$maxSize = $this->GetY() ;
		$ordonnee += $pos;
	}
	return ( $maxSize - $ligne );
}

function addRemarque($remarque)
{
	$this->SetFont( "Arial", "", 10);
	$length = $this->GetStringWidth( "Remarque : " . $remarque );
	$r1  = 10;
	$r2  = $r1 + $length;
	$y1  = $this->h - 45.5;
	$y2  = $y1+5;
	$this->SetXY( $r1 , $y1 );
	$this->Cell($length,4, "Remarque : " . $remarque);
}

function addCadreTVAs($monto)
{
	$this->SetFont( "Arial", "B", 8);
	$r1  = 10;
	$r2  = $r1 + 120;
	$y1  = $this->h - 40;
	$y2  = $y1+20;
	$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
	//$this->Line( $r1, $y1+4, $r2, $y1+4);
	//$this->Line( $r1+5,  $y1+4, $r1+5, $y2); // avant BASES HT
	//$this->Line( $r1+27, $y1, $r1+27, $y2);  // avant REMISE
	//$this->Line( $r1+43, $y1, $r1+43, $y2);  // avant MT TVA
	//$this->Line( $r1+63, $y1, $r1+63, $y2);  // avant % TVA
	//$this->Line( $r1+75, $y1, $r1+75, $y2);  // avant PORT
	//$this->Line( $r1+91, $y1, $r1+91, $y2);  // avant TOTAUX
	$this->SetXY( $r1+9, $y1+3);
	$this->Cell(10,4, "IMPORTE TOTAL CON LETRA");
	$this->SetFont( "Arial", "", 8);
	$this->SetXY( $r1+9, $y1+7);
	$this->MultiCell(100,4, $monto);
	//$this->SetX( $r1+29 );
	//$this->Cell(10,4, "REMISE");
	//$this->SetX( $r1+48 );
	//$this->Cell(10,4, "MT TVA");
	//$this->SetX( $r1+63 );
	//$this->Cell(10,4, "% TVA");
	//$this->SetX( $r1+78 );
	//$this->Cell(10,4, "PORT");
	//$this->SetX( $r1+100 );
	//$this->Cell(10,4, "TOTAUX");
	//$this->SetFont( "Arial", "B", 6);
	//$this->SetXY( $r1+93, $y2 - 8 );
	//$this->Cell(6,0, "H.T.   :");
	//$this->SetXY( $r1+93, $y2 - 3 );
	//$this->Cell(6,0, "T.V.A. :");
}

function addCadreEurosFrancs($impuesto)
{
	$r1  = $this->w - 70;
	$r2  = $r1 + 60;
	$y1  = $this->h - 40;
	$y2  = $y1+20;
	$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
	$this->Line( $r1+20,  $y1, $r1+20, $y2); // avant EUROS
	//$this->Line( $r1+20, $y1+4, $r2, $y1+4); // Sous Euros & Francs
	//$this->Line( $r1+38,  $y1, $r1+38, $y2); // Entre Euros & Francs
	$this->SetFont( "Arial", "B", 8);
	$this->SetXY( $r1+22, $y1 );
	$this->Cell(15,4, "TOTALES", 0, 0, "C");
	$this->SetFont( "Arial", "", 8);
	//$this->SetXY( $r1+42, $y1 );
	//$this->Cell(15,4, "FRANCS", 0, 0, "C");
	$this->SetFont( "Arial", "B", 6);
	$this->SetXY( $r1, $y1+5 );
	$this->Cell(20,4, "SUBTOTAL", 0, 0, "C");
	$this->SetXY( $r1, $y1+10 );
	$this->Cell(20,4, $impuesto, 0, 0, "C");
	$this->SetXY( $r1, $y1+15 );
	$this->Cell(20,4, "TOTAL A PAGAR", 0, 0, "C");
}

// remplit les cadres TVA / Totaux et la remarque
// params  = array( "RemiseGlobale" => [0|1],
//                      "remise_tva"     => [1|2...],  // {la remise s'applique sur ce code TVA}
//                      "remise"         => value,     // {montant de la remise}
//                      "remise_percent" => percent,   // {pourcentage de remise sur ce montant de TVA}
//                  "FraisPort"     => [0|1],
//                      "portTTC"        => value,     // montant des frais de ports TTC
//                                                     // par defaut la TVA = 19.6 %
//                      "portHT"         => value,     // montant des frais de ports HT
//                      "portTVA"        => tva_value, // valeur de la TVA a appliquer sur le montant HT
//                  "AccompteExige" => [0|1],
//                      "accompte"         => value    // montant de l'acompte (TTC)
//                      "accompte_percent" => percent  // pourcentage d'acompte (TTC)
//                  "Remarque" => "texte"              // texte
// tab_tva = array( "1"       => 19.6,
//                  "2"       => 5.5, ... );
// invoice = array( "px_unit" => value,
//                  "qte"     => qte,
//                  "tva"     => code_tva );
function addTVAs( $igv, $total,$moneda )
{
	$this->SetFont('Arial','',8);

	$re  = $this->w - 30;
	$rf  = $this->w - 29;
	$y1  = $this->h - 40;
	$this->SetFont( "Arial", "", 8);
	$this->SetXY( $re, $y1+5 );
	$this->Cell( 17,4, $moneda.sprintf("%0.2F", $total-($total*$igv/($igv+100))), '', '', 'R');
	$this->SetXY( $re, $y1+10 );
	$this->Cell( 17,4, $moneda.sprintf("%0.2F", ($total*$igv/($igv+100))), '', '', 'R');
	$this->SetXY( $re, $y1+14.8 );
	$this->Cell( 17,4, $moneda.sprintf("%0.2F", $total), '', '', 'R');
	
}

// add a watermark (temporary estimate, DUPLICATA...)
// call this method first
function temporaire( $texte )
{
	$this->SetFont('Arial','B',50);
	$this->SetTextColor(203,203,203);
	$this->Rotate(45,55,190);
	$this->Text(55,190,$texte);
	$this->Rotate(0);
	$this->SetTextColor(0,0,0);
}

}
?>

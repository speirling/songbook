<?php


$PageDefinitions = '/* Page Definitions */
 @page
	{mso-footnote-separator:url("source_files/header.htm") fs;
	mso-footnote-continuation-separator:url("source_files/header.htm") fcs;
	mso-endnote-separator:url("source_files/header.htm") es;
	mso-endnote-continuation-separator:url("source_files/header.htm") ecs;}
@page Section1
	{size:21.0cm 842.0pt;
	margin:55.3pt 51.05pt 55.3pt 51.05pt;
	mso-header-margin:35.45pt;
	mso-footer-margin:35.45pt;
	mso-page-numbers:roman-lower 1;
	mso-header:url("source_files/header.htm") h1;
	mso-footer:url("source_files/header.htm") f1;
	mso-paper-source:0;}
div.Section1
	{page:Section1;}
@page Section2
	{size:21.0cm 842.0pt;
	margin:55.3pt 51.05pt 55.3pt 51.05pt;
	mso-header-margin:35.45pt;
	mso-footer-margin:35.45pt;
	mso-page-numbers:roman-lower 1;
	mso-columns:2 even 16.75pt;
	mso-header:url("source_files/header.htm") h2;
	mso-footer:url("source_files/header.htm") f2;
	mso-paper-source:0;}
div.Section2
	{page:Section2;}
@page Section3
	{mso-page-numbers: 1;}
';

$pageDefinitionSingleColumn = 'size:21.0cm 842.0pt;
	margin:55.3pt 51.05pt 55.3pt 51.05pt;
	mso-header-margin:35.45pt;
	mso-footer-margin:35.45pt;
	mso-header:url("source_files/header.htm") h3;
	mso-footer:url("source_files/header.htm") f3;
	mso-paper-source:0;';

$pageDefinitionDoubleColumn = 'mso-columns:2 even 16.65pt;';


$fileHeaderBeforeTitle = '<html xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
<meta name=ProgId content=Word.Document>
<meta name=Generator content="Microsoft Word 11">
<meta name=Originator content="Microsoft Word 11">
<link rel=File-List href="source_files/filelist.xml">
<title>';

$fileHeaderFromTitleToPageDefinitions = '</title>
<!--[if gte mso 9]><xml>
 <o:DocumentProperties>
  <o:Author>Eugene Peelo</o:Author>
  <o:LastAuthor>Eugene Peelo</o:LastAuthor>
  <o:Revision>13</o:Revision>
  <o:TotalTime>135</o:TotalTime>
  <o:Created>2011-03-13T16:19:00Z</o:Created>
  <o:LastSaved>2011-03-14T22:45:00Z</o:LastSaved>
  <o:Pages>1</o:Pages>
  <o:Words>1101</o:Words>
  <o:Characters>6277</o:Characters>
  <o:Company>none</o:Company>
  <o:Lines>52</o:Lines>
  <o:Paragraphs>14</o:Paragraphs>
  <o:CharactersWithSpaces>7364</o:CharactersWithSpaces>
  <o:Version>11.9999</o:Version>
 </o:DocumentProperties>
</xml><![endif]--><!--[if gte mso 9]><xml>
 <w:WordDocument>
  <w:View>Print</w:View>
  <w:Zoom>91</w:Zoom>
  <w:PunctuationKerning/>
  <w:ValidateAgainstSchemas/>
  <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid>
  <w:IgnoreMixedContent>false</w:IgnoreMixedContent>
  <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText>
  <w:Compatibility>
   <w:BreakWrappedTables/>
   <w:SnapToGridInCell/>
   <w:WrapTextWithPunct/>
   <w:UseAsianBreakRules/>
   <w:DontGrowAutofit/>
  </w:Compatibility>
  <w:BrowserLevel>MicrosoftInternetExplorer4</w:BrowserLevel>
 </w:WordDocument>
</xml><![endif]--><!--[if gte mso 9]><xml>
 <w:LatentStyles DefLockedState="false" LatentStyleCount="156">
  <w:LsdException Locked="true" Name="Normal"/>
  <w:LsdException Locked="true" Name="heading 1"/>
  <w:LsdException Locked="true" Name="heading 2"/>
  <w:LsdException Locked="true" Name="heading 3"/>
  <w:LsdException Locked="true" Name="heading 4"/>
  <w:LsdException Locked="true" Name="heading 5"/>
  <w:LsdException Locked="true" Name="heading 6"/>
  <w:LsdException Locked="true" Name="heading 7"/>
  <w:LsdException Locked="true" Name="heading 8"/>
  <w:LsdException Locked="true" Name="heading 9"/>
  <w:LsdException Locked="true" Name="toc 1"/>
  <w:LsdException Locked="true" Name="toc 2"/>
  <w:LsdException Locked="true" Name="toc 3"/>
  <w:LsdException Locked="true" Name="toc 4"/>
  <w:LsdException Locked="true" Name="toc 5"/>
  <w:LsdException Locked="true" Name="toc 6"/>
  <w:LsdException Locked="true" Name="toc 7"/>
  <w:LsdException Locked="true" Name="toc 8"/>
  <w:LsdException Locked="true" Name="toc 9"/>
  <w:LsdException Locked="true" Name="caption"/>
  <w:LsdException Locked="true" Name="Title"/>
  <w:LsdException Locked="true" Name="Subtitle"/>
  <w:LsdException Locked="true" Name="Strong"/>
  <w:LsdException Locked="true" Name="Emphasis"/>
  <w:LsdException Locked="true" Name="Table Grid"/>
 </w:LatentStyles>
</xml><![endif]-->
<style>
<!--
 /* Font Definitions */
 @font-face
	{font-family:"Franklin Gothic Book";
	panose-1:2 11 5 3 2 1 2 2 2 4;
	mso-font-charset:0;
	mso-generic-font-family:swiss;
	mso-font-pitch:variable;
	mso-font-signature:647 0 0 0 159 0;}
@font-face
	{font-family:Verdana;
	panose-1:2 11 6 4 3 5 4 4 2 4;
	mso-font-charset:0;
	mso-generic-font-family:swiss;
	mso-font-pitch:variable;
	mso-font-signature:536871559 0 0 0 415 0;}
@font-face
	{font-family:Cambria;
	panose-1:2 4 5 3 5 4 6 3 2 4;
	mso-font-charset:0;
	mso-generic-font-family:roman;
	mso-font-pitch:variable;
	mso-font-signature:-1610611985 1073741899 0 0 159 0;}
@font-face
	{font-family:"Trebuchet MS";
	panose-1:2 11 6 3 2 2 2 2 2 4;
	mso-font-charset:0;
	mso-generic-font-family:swiss;
	mso-font-pitch:variable;
	mso-font-signature:647 0 0 0 159 0;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{mso-style-parent:"";
	margin:0cm;
	margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:12.0pt;
	font-family:"Times New Roman";
	mso-fareast-font-family:"Times New Roman";}
h1
	{mso-style-link:"Heading 1 Char";
	mso-style-next:Normal;
	margin-top:12.0pt;
	margin-right:0cm;
	margin-bottom:3.0pt;
	margin-left:0cm;
	mso-pagination:widow-orphan;
	page-break-after:avoid;
	mso-outline-level:1;
	font-size:16.0pt;
	font-family:Arial;
	mso-font-kerning:16.0pt;
	font-weight:bold;}
h2
	{mso-style-locked:yes;
	mso-style-next:Normal;
	margin-top:0cm;
	margin-right:0cm;
	margin-bottom:12.0pt;
	margin-left:0cm;
	mso-pagination:widow-orphan;
	page-break-after:avoid;
	mso-outline-level:2;
	font-size:10.0pt;
	font-family:Arial;
	color:gray;
	font-weight:normal;
	mso-bidi-font-weight:bold;
	mso-bidi-font-style:italic;}
p.MsoToc1, li.MsoToc1, div.MsoToc1
	{mso-style-update:auto;
	mso-style-noshow:yes;
	mso-style-locked:yes;
	mso-style-next:Normal;
	margin-top:6.0pt;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:0cm;
	margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	mso-bidi-font-size:12.0pt;
	font-family:Arial;
	mso-fareast-font-family:"Times New Roman";
	font-weight:bold;}
p.MsoToc2, li.MsoToc2, div.MsoToc2
	{mso-style-update:auto;
	mso-style-noshow:yes;
	mso-style-locked:yes;
	mso-style-next:Normal;
	margin:0cm;
	margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	tab-stops:right 513.0pt;
	font-size:6.0pt;
	font-family:Verdana;
	mso-fareast-font-family:"Times New Roman";
	mso-bidi-font-family:"Times New Roman";
	color:gray;
	mso-bidi-font-weight:bold;
	mso-no-proof:yes;}
p.MsoToc3, li.MsoToc3, div.MsoToc3
	{mso-style-update:auto;
	mso-style-noshow:yes;
	mso-style-locked:yes;
	mso-style-next:Normal;
	margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:12.0pt;
	margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	font-family:"Times New Roman";
	mso-fareast-font-family:"Times New Roman";}
p.MsoToc4, li.MsoToc4, div.MsoToc4
	{mso-style-update:auto;
	mso-style-noshow:yes;
	mso-style-locked:yes;
	mso-style-next:Normal;
	margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:24.0pt;
	margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	font-family:"Times New Roman";
	mso-fareast-font-family:"Times New Roman";}
p.MsoToc5, li.MsoToc5, div.MsoToc5
	{mso-style-update:auto;
	mso-style-noshow:yes;
	mso-style-locked:yes;
	mso-style-next:Normal;
	margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:36.0pt;
	margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	font-family:"Times New Roman";
	mso-fareast-font-family:"Times New Roman";}
p.MsoToc6, li.MsoToc6, div.MsoToc6
	{mso-style-update:auto;
	mso-style-noshow:yes;
	mso-style-locked:yes;
	mso-style-next:Normal;
	margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:48.0pt;
	margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	font-family:"Times New Roman";
	mso-fareast-font-family:"Times New Roman";}
p.MsoToc7, li.MsoToc7, div.MsoToc7
	{mso-style-update:auto;
	mso-style-noshow:yes;
	mso-style-locked:yes;
	mso-style-next:Normal;
	margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:60.0pt;
	margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	font-family:"Times New Roman";
	mso-fareast-font-family:"Times New Roman";}
p.MsoToc8, li.MsoToc8, div.MsoToc8
	{mso-style-update:auto;
	mso-style-noshow:yes;
	mso-style-locked:yes;
	mso-style-next:Normal;
	margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:72.0pt;
	margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	font-family:"Times New Roman";
	mso-fareast-font-family:"Times New Roman";}
p.MsoToc9, li.MsoToc9, div.MsoToc9
	{mso-style-update:auto;
	mso-style-noshow:yes;
	mso-style-locked:yes;
	mso-style-next:Normal;
	margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:84.0pt;
	margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	font-family:"Times New Roman";
	mso-fareast-font-family:"Times New Roman";}
p.MsoHeader, li.MsoHeader, div.MsoHeader
	{margin:0cm;
	margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	tab-stops:center 216.0pt right 432.0pt;
	border:none;
	mso-border-bottom-alt:solid teal 1.0pt;
	padding:0cm;
	mso-padding-alt:0cm 0cm 1.0pt 0cm;
	font-size:9.0pt;
	mso-bidi-font-size:12.0pt;
	font-family:"Trebuchet MS";
	mso-fareast-font-family:"Times New Roman";
	mso-bidi-font-family:"Times New Roman";
	color:teal;}
p.MsoFooter, li.MsoFooter, div.MsoFooter
	{margin:0cm;
	margin-bottom:.0001pt;
	text-align:right;
	mso-pagination:widow-orphan;
	tab-stops:center 216.0pt right 477.0pt;
	border:none;
	mso-border-top-alt:solid teal 1.0pt;
	padding:0cm;
	mso-padding-alt:1.0pt 0cm 0cm 0cm;
	font-size:8.0pt;
	font-family:Verdana;
	mso-fareast-font-family:"Times New Roman";
	mso-bidi-font-family:"Times New Roman";
	color:teal;}
span.MsoPageNumber
	{mso-ansi-font-size:8.0pt;
	font-family:"Trebuchet MS";
	mso-ascii-font-family:"Trebuchet MS";
	mso-hansi-font-family:"Trebuchet MS";}
a:link, span.MsoHyperlink
	{color:blue;
	text-decoration:underline;
	text-underline:single;}
a:visited, span.MsoHyperlinkFollowed
	{color:purple;
	text-decoration:underline;
	text-underline:single;}
p.MsoPlainText, li.MsoPlainText, div.MsoPlainText
	{mso-style-link:"Plain Text Char";
	margin:0cm;
	margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	font-family:"Courier New";
	mso-fareast-font-family:"Times New Roman";}
p
	{mso-margin-top-alt:auto;
	margin-right:0cm;
	mso-margin-bottom-alt:auto;
	margin-left:0cm;
	mso-pagination:widow-orphan;
	font-size:12.0pt;
	font-family:"Times New Roman";
	mso-fareast-font-family:"Times New Roman";}
span.Heading1Char
	{mso-style-name:"Heading 1 Char";
	mso-style-locked:yes;
	mso-style-link:"Heading 1";
	mso-ansi-font-size:16.0pt;
	mso-bidi-font-size:16.0pt;
	font-family:Cambria;
	mso-ascii-font-family:Cambria;
	mso-hansi-font-family:Cambria;
	mso-bidi-font-family:"Times New Roman";
	mso-font-kerning:16.0pt;
	font-weight:bold;}
span.PlainTextChar
	{mso-style-name:"Plain Text Char";
	mso-style-noshow:yes;
	mso-style-locked:yes;
	mso-style-link:"Plain Text";
	mso-ansi-font-size:10.0pt;
	mso-bidi-font-size:10.0pt;
	font-family:"Courier New";
	mso-ascii-font-family:"Courier New";
	mso-hansi-font-family:"Courier New";
	mso-bidi-font-family:"Courier New";}
p.lyrics, li.lyrics, div.lyrics
	{mso-style-name:lyrics;
	mso-style-parent:"Normal \(Web\)";
	mso-margin-top-alt:auto;
	margin-right:0cm;
	mso-margin-bottom-alt:auto;
	margin-left:0cm;
	mso-pagination:widow-orphan;
	border:none;
	mso-border-left-alt:solid gray .25pt;
	padding:0cm;
	mso-padding-alt:0cm 0cm 0cm 4.0pt;
	font-size:11.0pt;
	font-family:"Franklin Gothic Book";
	mso-fareast-font-family:"Times New Roman";
	mso-bidi-font-family:"Times New Roman";}
';


$fileHeaderAfterPageDefinitions = '
-->
</style>
<!--[if gte mso 10]>
<style>
 /* Style Definitions */
 table.MsoNormalTable
	{mso-style-name:"Table Normal";
	mso-tstyle-rowband-size:0;
	mso-tstyle-colband-size:0;
	mso-style-noshow:yes;
	mso-style-parent:"";
	mso-padding-alt:0cm 5.4pt 0cm 5.4pt;
	mso-para-margin:0cm;
	mso-para-margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	font-family:"Times New Roman";
	mso-ansi-language:#0400;
	mso-fareast-language:#0400;
	mso-bidi-language:#0400;}
</style>
<![endif]-->
</head>

<body lang=EN-US link=blue vlink=purple style=\'tab-interval:36.0pt\'>';

$titlePage = "<div class=Section1>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<div style='mso-element:para-border-div;border:none;border-right:solid teal 2.25pt;
padding:0cm 4.0pt 0cm 0cm'>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><b
style='mso-bidi-font-weight:normal'><span style='font-size:26.0pt;font-family:
\"Franklin Gothic Book\";color:#333333'>Singalong<o:p></o:p></span></b></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><b
style='mso-bidi-font-weight:normal'><span style='font-size:48.0pt;font-family:
\"Franklin Gothic Book\";color:teal'>Songbook<o:p></o:p></span></b></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-size:14.0pt;font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-size:14.0pt;font-family:\"Franklin Gothic Book\";color:teal'>Eugene
Peelo<o:p></o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-size:14.0pt;font-family:\"Franklin Gothic Book\"'>music@epeelo.com<o:p></o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=right style='text-align:right;border:none;mso-border-right-alt:
solid teal 2.25pt;padding:0cm;mso-padding-alt:0cm 4.0pt 0cm 0cm'><span
style='font-family:\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

</div>

<span style='font-size:12.0pt;font-family:\"Franklin Gothic Book\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:\"Times New Roman\";mso-ansi-language:
EN-US;mso-fareast-language:EN-US;mso-bidi-language:AR-SA'><br clear=all
style='mso-special-character:line-break;page-break-before:always'>
</span>

<p class=MsoNormal align=right style='text-align:right'><span style='font-family:
\"Franklin Gothic Book\"'><o:p>&nbsp;</o:p></span></p>

</div>

<span style='font-size:12.0pt;font-family:\"Franklin Gothic Book\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:\"Times New Roman\";mso-ansi-language:
EN-US;mso-fareast-language:EN-US;mso-bidi-language:AR-SA'><br clear=all
style='page-break-before:right;mso-break-type:section-break'>
</span>";

$contentsPage = "<div class=Section2>

<div style='mso-element:para-border-div;border:none;border-bottom:solid teal 1.0pt;
padding:0cm 0cm 1.0pt 0cm'>

<p class=MsoNormal style='border:none;mso-border-bottom-alt:solid teal 1.0pt;
padding:0cm;mso-padding-alt:0cm 0cm 1.0pt 0cm'><b style='mso-bidi-font-weight:
normal'><span style='font-size:20.0pt;font-family:Arial;color:teal'>Contents<o:p></o:p></span></b></p>

</div>

<p class=MsoToc1><!--[if supportFields]><span style='mso-element:field-begin'></span><span
style='mso-spacerun:yes'> </span>TOC \o &quot;1-3&quot; \h \z \u <span
style='mso-element:field-separator'></span><![endif]--></p>

<h1><!--[if supportFields]><span style='mso-element:field-end'></span><![endif]--><o:p>&nbsp;</o:p></h1>

<span style='font-size:12.0pt;font-family:\"Times New Roman\";mso-fareast-font-family:
\"Times New Roman\";mso-ansi-language:EN-US;mso-fareast-language:EN-US;
mso-bidi-language:AR-SA'><br clear=all style='mso-special-character:line-break;
page-break-before:always'>
</span>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

</div>

<b><span style='font-size:16.0pt;font-family:Arial;mso-fareast-font-family:
\"Times New Roman\";mso-font-kerning:16.0pt;mso-ansi-language:EN-US;mso-fareast-language:
EN-US;mso-bidi-language:AR-SA'><br clear=all style='page-break-before:right;
mso-break-type:section-break'>
</span></b>";

$pageBreak = "<br clear=all style='page-break-before:always; mso-break-type:section-break'>";

$footer = '</div>
</body>
</html>';
?>
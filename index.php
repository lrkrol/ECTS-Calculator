<!DOCTYPE html>

<!-- 
MIT License

Copyright (c) 2020 Laurens R. Krol

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
-->

<?php

if( isset( $_GET['ch'] ) ) {
    # getting indicated values
    $ch = filter_var($_GET['ch'], FILTER_SANITIZE_NUMBER_INT);
    $cd = filter_var($_GET['cd'], FILTER_SANITIZE_NUMBER_INT);
    $oh = filter_var($_GET['oh'], FILTER_SANITIZE_NUMBER_INT);
    $od = filter_var($_GET['od'], FILTER_SANITIZE_NUMBER_INT);
    $nw = filter_var($_GET['nw'], FILTER_SANITIZE_NUMBER_INT);
    $wl = filter_var($_GET['wl'], FILTER_SANITIZE_NUMBER_INT);
} else {
    # setting defaults
    $ch = 4;
    $cd = 45;
    $oh = 4;
    $od = 60;
    $nw = 15;
    $wl = '';
}

if( isset($wl) && $wl > 0 ) {
    # workload is meaningfully given;
    # calculating ECTS with indicated workload
    $ects = calculate_ects($ch, $cd, $oh, $od, $nw, $wl);
} else {
    # calculating ECTS with workload 1500 - 1800
    $ects1 = calculate_ects($ch, $cd, $oh, $od, $nw, 1800);
    $ects2 = calculate_ects($ch, $cd, $oh, $od, $nw, 1500);
    $ects = "{$ects1} - {$ects2}";
}

# getting some values to report
$classweekly = round( $ch * $cd  / 60, 1 );
$classtotal = $classweekly * $nw;
$otherweekly = round( $oh * $od  / 60, 1) ;
$othertotal = $otherweekly * $nw; 
$totalhours = $classtotal + $othertotal;

function calculate_ects($ch, $cd, $oh, $od, $nw, $wl) {
    return round( ( ( $ch * $cd ) + ( $oh * $od ) ) / 60 * $nw / ($wl / 60), 1 );
}

?>

<html lang="en">
    <head>
        <title>ECTS Calculator</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <style>    
            html        { font-family: sans-serif; }
            body        { max-width: 80ex;
                          margin: auto; }
            h1          { text-align: center; }
            fieldset    { margin-bottom: 2ex; }
            legend      { font-weight: bold; }
            label       { float: left; }
            .tooltip    { display: inline-block;
                          width: 2ex; height: 2ex;
                          background-color: lightgray;
                          border-radius: 1ex;
                          text-align: center;
                          vertical-align: middle;
                          font-size: small;
                          text-decoration: none; 
                          color: black; }
            .inputcont  { width: 18ex; float: right; margin-left: 1ex; margin-bottom: .5ex; }
                input               { width: 10ex; }
                input[type=number]  { text-align: right; }
            br          { clear: both; }
            hr          { clear: both; margin-top: .5ex; margin-bottom: 1ex;}
            #result     { text-align: center;
                          border: 1px solid lightgray;
                          padding: 1ex; 
                          font-size: large;
                          font-weight: bold; }
        </style>
    </head>
<body>

<h1>ECTS Calculator</h1>

<form method="get" action="./<?php echo basename(__FILE__); ?>">
    <fieldset>
        <legend>ECTS Calculator</legend>
        <label for="ch">Class hours per week <a class="tooltip" title="The number of hours per week that a student will be in class.">?</a></label>
            <div class="inputcont"><input type="number" id="ch" name="ch" value="<?php echo $ch; ?>" /> hours</div>
            <br />
        <label for="cd">Actual class hour duration <a class="tooltip" title="The actual duration of an academic 'hour' that a student will be in class; often, this is less than 60 minutes due to breaks etc.">?</a>:</label>
            <div class="inputcont"><input type="number" id="cd" name="cd" value="<?php echo $cd; ?>" /> minutes</div>
            <br />
            <hr />
        <label for="oh">Other hours per week <a class="tooltip" title="The number of hours per week that a student will be working for the course outside of class hours.">?</a>:</label>
            <div class="inputcont"><input type="number" id="oh" name="oh" value="<?php echo $oh; ?>" /> hours</div>
            <br />
        <label for="od">Actual other hour duration <a class="tooltip" title="The actual duration of an 'hour' that a student will be working for the course outside of class.">?</a>:</label>
            <div class="inputcont"><input type="number" id="od" name="od" value="<?php echo $od; ?>"  /> minutes</div>
            <br />
            <hr />
        <label for="wl">Number of weeks <a class="tooltip" title="The number of weeks the student will be working according to the indicated schedule.">?</a>:</label>
            <div class="inputcont"><input type="number" id="nw" name="nw" value="<?php echo $nw; ?>" /> weeks</div>
            <br />
            <hr />
        <label for="wl"><i>Optional:</i> Full-time yearly workload <a class="tooltip" title="The full-time workload in hours of an academic year, which equals 60 credits. If you know this number, fill it in here; otherwise, a range will be used.">?</a>:</label>
            <div class="inputcont"><input type="number" id="wl" name="wl" value="<?php echo $wl; ?>" /> hours</div>
            <br />
            <hr />
        <div class="inputcont"><input type="submit" value="Calculate"></div>
        <a href="./index.php">Reset</a>
    </fieldset>
</form>

<fieldset>
    <legend>Result</legend>
    <p>
        <?php echo "{$classtotal} total class hours ({$classweekly} per week) + {$othertotal} total other hours ({$otherweekly} per week) = {$totalhours} hours ="; ?>
    </p>
    
    <div id="result"><?php echo $ects; ?> ECTS</div>
</fieldset>

<fieldset>
    <legend>Explanation</legend>
    
    <p>This calculator helps you to calculate a course's ECTS given the total actual hours of work a student will perform. The <a href="https://op.europa.eu/s/n9bG" title="ECTS User Guide">ECTS User Guide of the European Union</a> states:</p>
    
    <blockquote>The correspondence of the full-time workload of an academic year to 60 credits is often formalised by national legal provisions. In most cases, workload ranges from 1,500 to 1,800 hours for an academic year, which means that one credit corresponds to 25 to 30 hours of work.</blockquote>
    
    <p>As such, the number of credits for a course depends on the total amount of hours worked for that course and on the total yearly workload. If you know the exact full-time workload of an academic year for your institution, this can optionally be indicated; otherwise, the guideline of 1500 to 1800 hours will be used and an ECTS range will be given.</p>
    
    <p>The used formula is:</p>
    
    <blockquote>((class hours * class hour duration) + (other hours * other hour duration)) / 60 minutes * number of weeks / (total workload / 60 ects)</blockquote>    
</fieldset>

</body>
</html>
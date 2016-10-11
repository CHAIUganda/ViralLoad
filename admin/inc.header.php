<?
//security check
if(!$GLOBALS['vlDC']) {
	die("<font face=arial size=2>Job 38:11</font>");
}

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="109"></td>
    <td class="search"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="date" width="300"><script>
var mydate=new Date()
var year=mydate.getYear()
if (year < 1000)
year+=1900
var day=mydate.getDay()
var month=mydate.getMonth()
var daym=mydate.getDate()
if (daym<10)
daym="0"+daym
var dayarray=new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday")
var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December")
document.write("<small>"+dayarray[day]+", "+montharray[month]+" "+daym+", "+year+"</small>")

</script></td>
          <td><form method="post" action="../admin/search.php" class="search">
              <table  border="0" cellpadding="0" cellspacing="0" class="search">
                <tr>
                  <td ><input type="text" name="REQ" class="box"></td>
                  <td>&nbsp;
                      <input name="Submit" type="image" value="GO" vspace="1" src="../admin/images/seach.gif" width="61" height="17"></td>
                </tr>
              </table>
          </form></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td></td>
    <td class="topnav"><table width="320" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="topbuton"><a href="../admin/publications.htm">publications</a></td>
          <td class="topbuton"><a href="../admin/library.htm">library</a></td>
          <td class="topbuton"><a href="../admin/events.htm">events</a></td>
          <td class="topbuton"><a href="../admin/contact_us.htm">contact us</a></td>
        </tr>
    </table></td>
  </tr>
</table>

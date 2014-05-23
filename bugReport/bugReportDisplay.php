<?php
$root=realpath($_SERVER['DOCUMENT_ROOT']);
include("$root/global/headers.php");

echo '
<form>
<table>
<tr>
  <th>Language (C/Java)*</th>
  <td>
  <SELECT id="language" name="language" >
					<OPTION VALUE="0">C Language</OPTION>
					<OPTION VALUE="1" selected >Java</OPTION>
  </SELECT>
  </td>
</tr>
<tr>
  <th> Summary*  </th>
  <td> <input type="text" id="summary" name="summary" size="50"/> </td>
</tr>
<tr>
  <th> Packages  </th>
  <td> <input type="text" id="packages" name="packages" size="50" /> </td>
</tr>

<tr>
  <th> Description* </th>
  <td> <textarea id="description1" rows="10" cols="50"></textarea> </td>
</tr>

</table>
<input type="submit" id="submit" value="Submit" onClick="bugReport();return false;" />
  <!--The return false here makes the browser not to reload the page after form is submitted.-->

</form>

<p>
<span class="blue">Fields marked * are mandatory</span></p>
';

?>

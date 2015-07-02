<html>
 <head>
  <title>Repository Analyser</title>
<script>
function textcheck()
{
var x=document.getElementById("startdate").value;
var y=document.getElementById("enddate").value;

if (x === "" && y === "")
{
 window.alert("Cannot leave Start date and end date empty field empty");
 return;
}
}
</script>
</head>
<body>
<form name="myform" onsubmit="return textcheck()" method="post" action="final.php">
  Start Date: <input type="date" name="startdate" id="startdate"/>
  End Date: <input type="date" name="enddate" id="enddate"/>
  <button id="submit" name="submit" >Submit </button>
</form>
<?php
if(isset($_POST["submit"])) 
{
 /*$stdate=date(DATE_ISO8601,strtotime('$_POST["startdate"]'));
 $endate=date(DATE_ISO8601,strtotime('$_POST["enddate"]'));*/
 $sedate=$_POST["startdate"];
 $eedate=$_POST["enddate"];

//Converting time to ISO time

 $time=strtotime($sedate);
 $times=date('c',$time);

 $sub=explode("+",$times);
 $stadate=$sub[0]."Z";

 $timee=strtotime($eedate);
 $timees=date('c',$timee);

 $subold=explode("+",$timees);
 $esdate=$subold[0]."Z";

 $sdate=$stadate;
 $edate=$esdate;

echo "<b>Start Date:</b>";
echo $sdate;
echo"<br />\n";

echo"<b>End Date:</b>";
echo $edate;
echo"<br />\n";



$optionsrepo  = array('http' => array('user_agent' => 'custom user agent string'));
$contextrepo  = stream_context_create($optionsrepo);
$responserepo = file_get_contents('https://api.github.com/orgs/thinkapps/repos?&access_token=e43c847959c77863874fb9a6d6e48f1364577672', false, $contextrepo);
$objrepo=json_decode($responserepo);

//Loop to get the Repo names
foreach ($objrepo as $repo) 
		{
				$reponame=$repo->name;
				echo"<b>Repo name:</b>";
				echo "<br />\n";
				echo $reponame;
				echo "<br />\n";


			$old='https://api.github.com/repos/thinkapps/';
			$add=$old.$reponame.'/branches';

//echo $old.$reponame.'/branches?access_token=e43c847959c77863874fb9a6d6e48f1364577672';

			$optionsbranch  = array('http' => array('user_agent' => 'custom user agent string'));
			$contextbranch  = stream_context_create($optionsbranch);
			$responsebranch = file_get_contents($old.$reponame.'/branches?access_token=e43c847959c77863874fb9a6d6e48f1364577672', false, $contextbranch);
			$objbranch=json_decode($responsebranch);

// Loop to get the branch names
			foreach ($objbranch as $b) 
			{
			//$br=$b->commit->sha;
			//echo $br;
				echo "<br />\n";
				echo"<b>Branch name: </b>";
				$br=$b->name;
				echo $br;
				echo "<br />\n";

				 $url= 'https://api.github.com/repos/thinkapps/';
				 $adding=$reponame.'/contributors';
//$appending=$url.$adding.'?sha='.$br.'&since='.$sdate.'&until='.$edate.'&access_token=e43c847959c77863874fb9a6d6e48f1364577672';
//echo $appending; 


                  $optionscontri  = array('http' => array('user_agent' => 'custom user agent string'));
				  $contextcontri  = stream_context_create($optionscontri);
				  $responsecontri = file_get_contents($url.$adding.'?sha='.$br.'&since='.$sdate.'&until='.$edate.'&access_token=e43c847959c77863874fb9a6d6e48f1364577672', false, $contextcontri);
				  $objcontri=json_decode($responsecontri);
//Loop to get contributors names
		foreach ($objcontri as $authname) 
		{
			$contriname=$authname->login;
			echo "<b>Author name</b>";
			echo $contriname;
		echo "<br />\n";

			$urlauth='https://api.github.com/repos/thinkapps/';
			$adds=$reponame.'/commits';
			//echo $urlauth.$adds.'?sha='.$br.'&author='.$contriname.'&since='.$sdate.'&until='.$edate.'&access_token=e43c847959c77863874fb9a6d6e48f1364577672';
               

             
                  $optionsbranchdetail  = array('http' => array('user_agent' => 'custom user agent string'));
				  $contextbranchdetail  = stream_context_create($optionsbranchdetail);
				  $responsebranchdetail = file_get_contents($urlauth.$adds.'?sha='.$br.'&author='.$contriname.'&since='.$sdate.'&until='.$edate.'&access_token=e43c847959c77863874fb9a6d6e48f1364577672', false, $contextbranchdetail);
				  $objbranchdetail=json_decode($responsebranchdetail);

					$count=0;
//Loop to get the sha value and commit count

		          foreach ($objbranchdetail as $shabranch)
 					{

 						$find=$shabranch->commit;
						$count=$count+1;
						echo "\n";
			 			$final=$shabranch->sha;
			 			
			 			//echo "bye";
			 			//echo $final;



			 			$urlnew= 'https://api.github.com/repos/thinkapps/';
			 			$addingnew=$reponame.'/commits/';
$appending=$urlnew.$addingnew.$final.'?sha='.$br.'&author='.$contriname.'&since='.$sdate.'&until='.$edate.'&access_token=e43c847959c77863874fb9a6d6e48f1364577672';
echo $appending; 
			      		echo "<br />\n";

						$optionsbranchfiles  = array('http' => array('user_agent' => 'custom user agent string'));
			  			$contextbranchfiles  = stream_context_create($optionsbranchfiles);
			  			$responsebranchfiles = file_get_contents($urlnew.$addingnew.$final.'?sha='.$br.'&author='.$contriname.'&since='.$sdate.'&until='.$edate.'&access_token=e43c847959c77863874fb9a6d6e48f1364577672', false, $contextbranchfiles);
			  			$objbranchfiles=json_decode($responsebranchfiles);
	


						$filenumber=$objbranchfiles->files;
//Loop to get the file detail - Name, status, addition , deletion , changes
	   					foreach ($filenumber as $key) 
	         			 {
     
   
						    echo "<br />\n";
						    echo "<b> filename: </b>";
						    echo $key->filename;
						    echo "<br />\n";
						  
						    echo "<b>Status:</b>";
						    echo $key->status;
						    echo "<br />\n";
						  
						    echo"<b>Additions:</b>";
						    echo $key->additions;
						    echo "<br />\n";
						  
						    echo"<b>Deletions:</b>";
						    echo $key->deletions;
						    echo "<br />\n";
						  
						    echo"<b>Changes:</b>";
						    echo $key->changes;
						    echo "<br />\n";
						    echo "<br />\n";
						    }
						  
  					}  

  					// Commit details
  						echo"<b>Commit Details </b>";
                    	echo "<br />\n";
                    	echo"<b> Total number of commits: </b>";
 					 	echo $count;
  						echo "<br />\n";

				
  					}

              }
				

				}
		}

//}
?>
</body>
</html>

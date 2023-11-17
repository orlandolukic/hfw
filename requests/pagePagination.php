<?php 

//  User attempt to open this page
if (!(isset($REDIRECT) && !$REDIRECT)) header("location: ../index.php"); $REDIRECT = NULL;

//	Place in document where has to be printed data

if ($page===0) $GET_PAGE = 1;
$setGO = true;
$grantAccess = false;
?>
	<li class="<?= ($GET_PAGE===1 ? "disabled" : "") ?>"><a href="<?= ($GET_PAGE===1 ? "javascript: void(0)" : $URL_to_redirect."&page=".($GET_PAGE-1)) ?>">&laquo;</a></li> 
<?php
switch(true)
{
case ($GET_PAGE <= 5): 
	for ($i=1; $i<=$GET_PAGE+2; $i++) { if ($i>$DATA_SQL->pages) continue;  ?>
		<li class="<?= ($i===$GET_PAGE ? "active" : "") ?>"><a href="<?= ($i===$GET_PAGE ? "javascript: void(0)" : $URL_to_redirect."&page=".$i) ?>"><?= $i ?></a></li>
	<?php };
	if ($DATA_SQL->pages > $GET_PAGE+2) $grantAccess = true;
	break;

case ($GET_PAGE > 5): ?>
	<li><a href="<?= $URL_to_redirect; ?>&page=1">1</a></li>
	<li><a href="<?= $URL_to_redirect; ?>&page=2">2</a></li>
	<li><a href="javascript: void(0)">...</a></li>
<?php	
	for ($i = $GET_PAGE-2; $i <= ($GET_PAGE+3 === $DATA_SQL->pages ? $DATA_SQL->pages : $GET_PAGE+2 ); $i++) { if ($i>$DATA_SQL->pages) continue; ?>
		<li class="<?= ($i===$GET_PAGE ? "active" : "") ?>"><a href="<?= ($i===$GET_PAGE ? "javascript: void(0)" : $URL_to_redirect."&page=".$i) ?>"><?= $i ?></a></li>
<?php };
	if ($GET_PAGE+3 >= $DATA_SQL->pages) $setGO = false; else $grantAccess = true;
	break;

};

if ($GET_PAGE >= $DATA_SQL->pages-4 && $DATA_SQL->pages-$GET_PAGE>2 && $setGO || $grantAccess) { ?>
	<li><a href="javascript: void(0)">...</a></li>
	<li><a href="<?= $URL_to_redirect; ?>&page=<?= $DATA_SQL->pages ?>"><?= $DATA_SQL->pages ?></a></li>
<?php }


?>

<li class="<?= ($GET_PAGE==$DATA_SQL->pages ? "disabled" : "") ?>"><a href="<?= ($GET_PAGE==$DATA_SQL->pages ? "javascript: void(0)" : $URL_to_redirect."&page=".($GET_PAGE+1)) ?>">&raquo;</a></li>

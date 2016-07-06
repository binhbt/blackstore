<?php

global $bbit;
echo json_encode(
		$bbit->loadRichSnippets('options')
);

?>
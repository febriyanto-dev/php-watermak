<?php
    function gitVersion()
    {
        $tag  = exec('git describe --tags --abbrev=0');
        
        if(empty($tag)) {
            $tag = '-.-.-';
        }

        $hash = trim(exec('git log --pretty="%h" -n1 HEAD'));

        return $hash;
    }
?>

<?php

function br($i = 1) {
    for(;$i > 0; $i--) {
        echo '</br>';
    }
}

function getConfig() {
    return new Config();
}
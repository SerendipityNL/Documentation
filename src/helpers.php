<?php

function hl( $file, $syntax )
{
    return \TrafficSupply\Documentation\Highlighter::file( $file, $syntax );
}

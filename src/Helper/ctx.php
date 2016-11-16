<?php

/**
 * ctx快捷方法
 */
function ctx($ctxBase, $ctxNamespace = 'Ctx')
{
    return Tree6bee\Ctx\Ctx::getInstance($ctxBase, $ctxNamespace);
}

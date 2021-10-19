<?php
// 应用公共文件

/**
 * 替代抛出异常fail
 * @author Plum
 * @email 18685850590@163.com
 * @time 2021年10月03日 00:34
 */
function abort($message)
{
    throw new \plum\core\exception\FailException($message);
}

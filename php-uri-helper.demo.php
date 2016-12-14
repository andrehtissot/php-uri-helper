<?php
include 'php-uri-helper.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PHP URI Helper Tests</title>
    <style>
        table {
            border-spacing: 0px;
            max-width: 100%;
        }
        th,td {
            border-left: 1px solid black;
            border-top: 1px solid black;
            padding: 6px;
        }
        tbody tr:last-child td {
            border-bottom: 1px solid black;
        }
        tbody td:last-child,thead th:last-child {
            border-right: 1px solid black;
        }
        pre {
            display: inline;
        }
        h4 {
            margin-bottom: 10px;
            margin-top: 60px;
        }
        div {
            margin-bottom: 10px;
        }
        h2+h4 {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <h2>PHP URI Helper Tests</h2>
    <h4>Current State Methods</h4>
    <div>
        If necessary to force an application path, in a shared host for example,
        use <pre>URIHelper::forceApplicationPath(</pre>[application directory as String]<pre>)</pre>.
    </div>
    <table>
        <thead><tr><th>Description</th><th>Method</th><th>Return Type</th><th>Return</th></tr></thead>
        <tbody>
            <tr>
                <td>Application Path</td><td>URIHelper::currentApplicationPath()</td>
                <td>String</td><td><?= URIHelper::currentApplicationPath() ?></td>
            </tr>
            <tr>
                <td>Host</td><td>URIHelper::currentHost()</td>
                <td>String</td><td><?= URIHelper::currentHost() ?></td>
            </tr>
            <tr>
                <td>Port</td><td>URIHelper::currentPort()</td>
                <td>Integer</td><td><?= URIHelper::currentPort() ?></td>
            </tr>
            <tr>
                <td>SSL</td><td>URIHelper::currentSSL()</td>
                <td>Boolean</td><td><pre><?= URIHelper::currentSSL() ? 'true' : 'false' ?></pre></td>
            </tr>
            <tr>
                <td>Protocol</td><td>URIHelper::currentProtocol()</td>
                <td>String</td><td><?= URIHelper::currentProtocol() ?></td>
            </tr>
            <tr>
                <td>Path</td><td>URIHelper::currentPath()</td>
                <td>String</td><td><?= URIHelper::currentPath() ?></td>
            </tr>
            <tr>
                <td>Params</td><td>URIHelper::currentParams()</td>
                <td>Array</td><td><pre><? print_r(URIHelper::currentParams()) ?></pre></td>
            </tr>
        </tbody>
    </table>
    <? foreach (array('generateFromApplicationRoot()' => URIHelper::generateFromApplicationRoot(),
        'generateFromCurrent()' => URIHelper::generateFromCurrent(),
        'from("https://github.com/andrehtissot/php-uri-helper")' =>
            URIHelper::from("https://github.com/andrehtissot/php-uri-helper"),
        'from("https://secure.php.net/manual/pt_BR/ini.core.php?tst=1#ini.always-populate-raw-post-data")' =>
            URIHelper::from("https://secure.php.net/manual/pt_BR/ini.core.php?tst=1#ini.always-populate-raw-post-data"),
            'from(URIHelper::currentFull())' => URIHelper::from(URIHelper::currentFull()),
        'from(URIHelper::currentApplicationRoot())' => URIHelper::from(URIHelper::currentApplicationRoot()) )
        as $gerenerationMethod => $URIObject) { ?>
        <h4>
            Methods from <pre>URIObject</pre> gerenerated with:
            <pre>URIHelper::<?= $gerenerationMethod ?></pre>
        </h4>
        <table>
            <thead><tr><th>Description</th><th>Method</th><th>Return Type</th><th>Return</th></tr></thead>
            <tbody>
                <tr><td>Full URI address</td><td>full()</td><td>String</td><td><?= $URIObject->full() ?></td></tr>
                <tr>
                    <td>If converted to String</td><td>__toString()</td>
                    <td>String</td><td><?= ''.$URIObject ?></td>
                </tr>
                <tr>
                    <td rowspan="9">Format values like given pattern</td><td>format()</td>
                    <td rowspan="9">String</td><td><?= $URIObject->format() ?></td>
                </tr>
                <tr><td>format('PROTOCOL')</td><td><?= $URIObject->format('PROTOCOL') ?></td></tr>
                <tr><td>format('HOST')</td><td><?= $URIObject->format('HOST') ?></td></tr>
                <tr><td>format('PORT')</td><td><?= $URIObject->format('PORT') ?></td></tr>
                <tr>
                    <td>format('PORT_OR_HIDDEN_IF_DEFAULT')</td>
                    <td><?= $URIObject->format('PORT_OR_HIDDEN_IF_DEFAULT') ?></td>
                </tr>
                <tr>
                    <td>format('APPLICATION_ROUTE')</td>
                    <td><?= $URIObject->format('APPLICATION_ROUTE') ?></td>
                </tr>
                <tr><td>format('PATH')</td><td><?= $URIObject->format('PATH') ?></td></tr>
                <tr><td>format('QUERY')</td><td><?= $URIObject->format('QUERY') ?></td></tr>
                <tr><td>format('HASH')</td><td><?= $URIObject->format('HASH') ?></td></tr>
                <tr>
                    <td>Set/add a parameter value</td><td>setParam('test', 'testValue')</td>
                    <td>\URIHelper\URIObject</td><td><?= $URIObject->setParam('test', 'testValue') ?></td>
                </tr>
                <tr>
                    <td>Get a parameter value</td><td>getParam('test')</td>
                    <td>String</td><td><?= $URIObject->getParam('test') ?></td>
                </tr>
                <tr>
                    <td>Remove a parameter</td><td>removeParam('test')</td>
                    <td>\URIHelper\URIObject</td><td><?= $URIObject->removeParam('test') ?></td>
                </tr>
                <tr>
                    <td>Set/add parameter values</td><td>setParams(array('test1' => 'testValue1',
                        'test2' => 'testValue2', 'test3' => 'testValue3'))</td>
                    <td>\URIHelper\URIObject</td><td><?= $URIObject->setParams(array('test1' => 'testValue1',
                        'test2' => 'testValue2', 'test3' => 'testValue3')) ?></td>
                </tr>
                <tr>
                    <td>Get all parameter values</td><td>getParams()</td>
                    <td>Array</td><td><pre><? print_r($URIObject->getParams()) ?></pre></td>
                </tr>
                <tr>
                    <td>Remove parameters</td><td>removeParams(array('test1','test2'))</td>
                    <td>\URIHelper\URIObject</td><td><?= $URIObject->removeParams(array('test1','test2')) ?></td>
                </tr>
            </tbody>
        </table>
    <? } ?>
    <br />
</body>
</html>

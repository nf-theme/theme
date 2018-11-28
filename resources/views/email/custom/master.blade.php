<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <style type="text/css" rel="stylesheet" media="all">
        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
            p {
                font-size: 14px;
            }
        }
        </style>
</head>
<?php

$style = [
    /* Layout ------------------------------ */
    'full-width'          => 'width: 100%;',
    'body'                => 'margin: 0; padding: 0; width: 100%; background-color: #fff;',
    'email-wrapper'       => 'width: 100%; margin: 0; padding: 0; background-color: #fff;',

    /* Masthead ----------------------- */

    'email-masthead'      => 'text-align: center;',
    'email-masthead_name' => 'font-size: 16px; font-weight: bold; color: #2F3133; text-decoration: none; text-shadow: 0 1px 0 white; display: block;',

    'email-body'          => 'width: 100%; margin: 0; padding: 0; background-color: #FFF;',
    'email-body_cell'     => 'padding: 22px 0px 30px 0px;',

    /* Body ------------------------------ */

    'body_action'         => 'width: 100%; margin: 30px auto; padding: 0; text-align: center;',
    'body_sub'            => 'margin-top: 25px; padding-top: 25px; border-top: 1px solid #EDEFF2;',

    /* Type ------------------------------ */

    'header-1'            => 'color: #202020; font-size: 25px; font-weight: bold; text-align: center;',
];

$custom_style['font-light']  = "font-family: museo_sans100, 'open sans', 'helvetica neue', helvetica,arial, sans-serif;";
$custom_style['font-normal'] = "font-family: museo_sans300, 'open sans', 'helvetica neue', helvetica,arial, sans-serif;";
$custom_style['font-bold']   = "font-family: museo_sans500, 'open sans', 'helvetica neue', helvetica,arial, sans-serif;";
?>

    <body style="{{ $style['body'] }}">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td style="{{ $style['email-wrapper'] }}" align="center">
                    <table width="600" cellpadding="0" cellspacing="0">
                        <!-- Email Body -->
                        <tr>
                            <td style="{{ $style['email-body'] }}" width="100%">
                                <table style="{{ $style['full-width'] }}" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="{{ $custom_style['font-normal'] }} {{ $style['email-body_cell'] }}">
                                            <h1 style="{{ $custom_style['font-bold'] }} {{ $style['header-1'] }}">@yield('subject')</h1>
                                            <!-- Greeting -->
                                            @if(isset($greeting))
                                            <div style="padding-top: 40px; {{$custom_style['font-normal']}}">@yield('greeting')</div>
                                            @endif
                                            <div>@yield('content')</div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr style="background-color: #fbfbfb;">
                            <td style="padding: 9px 0; border-top: 2px solid #EAEAEA;">
                                <table cellpadding="0" cellspacing="0" style="{{ $style['full-width'] }}">
                                    <tr>
                                        <td style="padding: 9px 0 3px 0;">
                                            <p style="text-align: center; color: #656565; font-size: 12px; font-style: italic;{{$custom_style['font-normal']}}">Copyright © 2018 Vicoders, All rights reserved. </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>

</html>

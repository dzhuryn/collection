<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="media/style/common/bootstrap/css/bootstrap.min.css?v=4.3.1" />
    <link rel="stylesheet" type="text/css" href="media/style/common/font-awesome/css/font-awesome.min.css?v=4.7.0" />

    <link rel="stylesheet" type="text/css" href="media/style/[+manager_theme+]/css/fonts.css?v=1.3.5" />
    <link rel="stylesheet" type="text/css" href="media/style/[+manager_theme+]/css/f1orms.css?v=1.3.5" />
    <link rel="stylesheet" type="text/css" href="media/style/[+manager_theme+]/css/custom.css?v=1.3.5" />
    <link rel="stylesheet" type="text/css" href="media/style/[+manager_theme+]/css/tabpane.css" />
    <link rel="stylesheet" type="text/css" href="media/style/[+manager_theme+]/css/contextmenu.css" />
    <link rel="stylesheet" type="text/css" href="media/style/[+manager_theme+]/css/main.css" />
    <!--
    <link rel="stylesheet" type="text/css" href="media/style/common/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="media/style/common/font-awesome/css/font-awesome.min.css" />

    <link rel="stylesheet" type="text/css" href="media/style/[+manager_theme+]/css/fonts.css" />
    <link rel="stylesheet" type="text/css" href="media/style/[+manager_theme+]/css/css/custom.css" />
    <link rel="stylesheet" type="text/css" href="media/style/[+manager_theme+]/css/css/tabpane.css" />
    <link rel="stylesheet" type="text/css" href="media/style/[+manager_theme+]/css/css/contextmenu.css" />
    <link rel="stylesheet" type="text/css" href="media/style/[+manager_theme+]/css/css/main.css" />

-->








    <link type="text/css" rel="stylesheet" href="../assets/lib/webix/codebase/skins/compact.css">
    <style>

        body{
            width: 100%;
            overflow-x: hidden;
        }
        .checked {color:#65B688;text-align:center;cursor:pointer;}
        .notchecked {color: #D65C4F;text-align:center;cursor:pointer;}
        .myhover, .webix_row_select {background: #e1f6fd !important;}
        .webix_row_select {color:#000!important;}
        .webix_ss_header TD { background-color: #e9e9e9;}
        .webix_ss_header td{color: #333; border-right: 1px solid #e1e1e1;border-bottom: 1px solid #e1e1e1;}
        .ec_pager {margin: 10px 0 0 0; padding: 5px 0 0 0;  border-top: #eee 1px solid;}
        .ec_pager .webix_pager button {background: #e1e1e1; color: #333; padding: .3em 0px; border: none; min-width: 2em;}
        .ec_pager .webix_pager button:hover {background: #31b0d5; color: #fff;}
        .ec_pager .webix_pager button.webix_pager_item_selected{background: transparent; pointer-events: none; color: #2b9385; border: #2b9385 1px solid;}
        .ec_pager .webix_pager button:first-of-type:last-of-type {display: none;}
        .image-thumb {
            max-height: 100%;
            width: auto;
            -webkit-transition:all 0.5s ease;
            -moz-transition:all 0.5s ease;
            -o-transition:all 0.5s ease;
            transition:all 0.5s ease;
        }
        .image-thumb:hover {
            -webkit-transform:scale(1.03);
            -moz-transform:scale(1.03);
            -o-transform:scale(1.03);transform:scale(1.03);
        }
        .image-tooltip{
            width:200px;
        }
        .un-publish {
            color: #d0726b !important;
        }
        .deleted {
            color: red !important;
        }
        .top-actions .group{
            text-align: right;
            white-space: nowrap;
            margin: 0 0 10px 0;
        }
        .top-actions select{
            display: inline-block;
            height: 2.55em;
            width:auto;
            max-width: 300px;
        }


        .top-actions .mass-action-field-value-owner{
            display: inline-block;
        }
        img[src*='noimage'] {display:none;}
        .image-thumb.js-add-image{
            display: inline-block;
        }
        .image-cell .webix_cell{
            padding: 0 5px;
        }
        .edit.btn {
            padding: .4em;
            vertical-align: middle;
        }
        .webix_dt_editor select{
            height: 100% !important;
            line-height: 100% !important;

        }

        .webix_el_search input, .webix_el_text input, .webix_el_combo input {
            border-radius: 0 !important;
        }
    </style>
    <script src="../assets/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="../assets/lib/webix/codebase/webix.js"></script>
</head>
<body>
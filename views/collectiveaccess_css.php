<?php
    // asset path
    $expand_icon_url = plugins_url( 'assets/images/icon-expand.png', WP_CA_MAIN_FILE);
    $loading_icon_url = plugins_url( 'assets/images/icon-loading.png', WP_CA_MAIN_FILE);
    $shrink_icon_url = plugins_url( 'assets/images/icon-shrink.png', WP_CA_MAIN_FILE);
    $empty_icon_url = plugins_url( 'assets/images/icon-empty.png', WP_CA_MAIN_FILE);
?>

figure.gallery-item p {display:none;}

.collectiveaccess-cropping-image {
    height:170px;
    width:150px;
    overflow:hidden;
    background-color: black;
    text-align: center;
    vertical-align: middle;
    display:table-cell;
}

.collapsed {
}
p.facetname {
    border-top:1px solid lightgray;
    margin-bottom:10px;
    margin-top:20px;
}
p.facetname:first-child {
    border:none;
}
.facetname, .subfacetname {
    cursor:pointer;    
}
.subfacet {
    margin-right:10px;
}
.subfacet.collapsed:before {
    content : '+';
}
.subfacet:before {
    content : '-';
    background-color: #24890d;
    border: 0;
    border-radius: 2px;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    padding: 2px 4px 2px 4px;
    margin-right:3px;
    text-transform: uppercase;
    width:25px;
    display:inline-box;
    min-width:25px;
}
.remove-criterias a {
    text-decoration:none;
    cursor:pointer;
}
.remove-criterias a:hover,
.remove-criterias a:hover:before {
    text-decoration: line-through;
}
.reset-criterias a {
    background: #24890d;
    color: #fff;
    text-shadow: none;
    padding-bottom: 3px;
    padding-left: 12px;
    padding-right: 12px;
    padding-top: 3px
}
.reset-criterias a:hover{
    background-color: rgb(65, 166, 42);
    color:#fff;
}

.wpca-parent-container {
    margin:4px 0 4px 20px;
}

.toggler {
    background:#009112;
    margin-right:4px;
    padding:3px 3px 0 3px;
    border-radius:3px;
}

.fold > .toggler-icon {
    content:url('<?php print $expand_icon_url; ?>');
}
.unfold > .toggler-icon {
    content:url('<?php print $shrink_icon_url; ?>');
}
.empty > .toggler-icon {
    content:url('<?php print $empty_icon_url; ?>');
}

.loading > .toggler-icon {
    content:url('<?php print $loading_icon_url; ?>');
    -webkit-animation: spin 0.5s infinite linear;
    -moz-animation: spin 0.5s infinite linear;
    -ms-animation: spin 0.5s infinite linear;
    animation: spin 0.5s infinite linear;
}


.wpca-parent-title {
}

@-moz-keyframes spin { 100% { -moz-transform: rotate(360deg); } }
@-webkit-keyframes spin { 100% { -webkit-transform: rotate(360deg); } }
@keyframes spin { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); } }

.linked_objects_title {
    font-family: Lato, sans-serif;
    font-size: 22px;
    text-transform:uppercase;
    font-style: normal;
    font-weight: 300;
    margin:10px 0 14px 0; 
}

.linked_object {
    float:left;
    width:80px;
    margin-right:10px;
    margin-bottom:10px;
}

.linked_representation_object {
    height:80px;
    background-color:black;
    display:table-cell;
    vertical-align:middle;
}

.linked_object_name {
    font-size:12px;
}
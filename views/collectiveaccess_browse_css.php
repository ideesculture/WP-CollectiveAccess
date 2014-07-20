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

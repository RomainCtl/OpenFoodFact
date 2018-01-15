var countCrit=1,
    countNut=1,
    limitCrit=5,
    limitNut=16;

function addCrite(){
    if (countCrit < limitCrit) {
        countCrit = addElem("firstcrit", "critlist", countCrit);
        if (countCrit == limitCrit) document.getElementById("addCrit").parentElement.removeChild(document.getElementById("addCrit"));
    }
}

function addNutr(){
    if (countNut < limitNut) {
        countNut = addElem("firstnut", "nutlist", countNut);
        if (countNut == limitNut) document.getElementById("addNut").parentElement.removeChild(document.getElementById("addNut"));
    }
}

function addNutr2(){
    if (countNut < limitNut) {
        var t = document.getElementById("nutrimselect");
        var tt = t.options[t.selectedIndex];
        var k = tt.innerText;
        document.getElementById("nutri").appendChild(k+" : <input type='number' value='valnut[]'/>");
        if (countNut == limitNut) document.getElementById("addNut").parentElement.removeChild(document.getElementById("addNut"));
    }
}

function addElem(model, father, count){
    var elem = document.createElement('div');
    elem.innerHTML = document.getElementById(model).innerHTML;
    document.getElementById(father).appendChild(elem);
    count++;
    return count;
}

function addAdditives(){
    moveto("additivesselect", "addiselected");
}

function removeAdditives(){
    moveto("addiselected", "additivesselect");
}

function addCountries(){
    moveto("countiresselect", "countrieselected");
}

function removeCountries(){
    moveto("countrieselected", "countiresselect");
}

function moveto(list, other){
    var list = document.getElementById(list);
    var elem = list.options[list.selectedIndex];

    if (elem.value != 'none') {
        document.getElementById(other).appendChild(elem);
        list.removeChild(elem);
    }
}
var countCrit=1,
    countNut=1,
    limitCrit=8,
    limitNut=8;

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

function addElem(model, father, count){
    var elem = document.createElement('div');
    elem.innerHTML = document.getElementById(model).innerHTML;
    document.getElementById(father).appendChild(elem);
    return count++;
}
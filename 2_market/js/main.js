let title = document.getElementById("title");
let price = document.getElementById("price");
let taxes = document.getElementById("taxes");
let ads = document.getElementById("ads");
let discount = document.getElementById("discount");
let total = document.getElementById("total");
let count = document.getElementById("count");
let category = document.getElementById("category");
let submit = document.getElementById("submit");

// ---------------------------------------------------------------
// to test the varible get

// console.log(title,taxes,price,ads,discount,total,count,category,submit);

// ---------------------------------------------------------------

function gettotal() {
    // console.log("done");
    if(price.value !=""){
       let  result=(+price.value + +taxes.value + +ads.value)- +discount.value;
        total.innerHTML=result;
        total.style.background="green";
    }else{
        total.innerHTML="0000";
        total.style.background="red";

    }
}
// ---------------------------------------------------------------
// make to title

let databox;

if(localStorage.product != null){
    databox = JSON.parse(localStorage.product)
}else{
    databox=[];
}

submit.onclick =function(){
let new_obj ={
    title:title.value,
    price:price.value,
    taxes:taxes.value,
    ads:ads.value,
    discount:discount.value,
    total:total.innerHTML,
    count:count.value,
    category:category.value

   }
   databox.push("new_obj")
   localStorage.setItem('product',     JSON.stringify(databox));
   console.log(databox);
}

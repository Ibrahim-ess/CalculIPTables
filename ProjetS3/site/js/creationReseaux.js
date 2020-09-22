/*
    Ce fichier comporte toutes les fonctions pour l'exercice sur les tables de routage.
*/
function drawTextBG(ctx, txt, font, x, y, padding) {
    padding = padding || 5;
    
    //ctx.save();
    ctx.font = font;
    ctx.textBaseline = 'top';
    ctx.fillStyle = '#ff0';
    
    var width = ctx.measureText(txt).width;
    ctx.fillRect(x-padding, y-padding, width+(padding*2), parseInt(font, 10) + (padding*2));
    
    ctx.fillStyle = '#000';
    ctx.fillText(txt, x, y);
    
    //ctx.restore();
}

//Permet de dessiner une ligne sur le canvas. Cela représente un cable.
function construireLigne(x,y,x1,y1){
    var c = document.getElementById("canvasNetwork");
    var ctx = c.getContext("2d");
    ctx.strokeStyle = "#999999";
    ctx.beginPath();
    ctx.moveTo(x,y);
    ctx.lineTo(x1,y1);
    ctx.stroke();
}

//Définit les caractéristiques d'une machine
function Machine(x,y,width,height,image,nom) {
    this.nbportB=0;
    this.nbportH=0;
    this.x=x;
    this.y=y;
    this.width=width;
    this.height=height;
    this.image=image;
    this.nom=nom;
}

//Permet de dessiner une machine sur le canvas
function drawMachine(machine,id){
    var c = document.getElementById(id);
    var ctx = c.getContext("2d");
    var img=new Image();
    img.src = 'images/'+machine.image+'.png';
    img.onload = function(){
        ctx.drawImage(img,machine.x,machine.y,machine.width,machine.height);
    ctx.font = "Bold 16px Arial";
    ctx.fillText(machine.nom,machine.x+(machine.width/2)-(machine.nom.length/2)*11,machine.y-2);

    }
}

//Fonction appelé pour dessiner un cable
function drawLigne(ligne,id){
    var c = document.getElementById(id);
    var ctx = c.getContext("2d");
    ctx.font = "12px Arial";
    ctx.fillStyle = "#000000";
    construireLigne(ligne.x,ligne.y,ligne.x1,ligne.y1);
    ctx.fillText(ligne.p1_port_nom,ligne.p1_x,ligne.p1_y);
    ctx.fillText(ligne.p2_port_nom,ligne.p2_x,ligne.p2_y);
}

//Permet d'afficher le port de la machine et de la relier avec une autre
function relierDeuxPoints(p1,p2,port1,port2){
    this.p1_nom=p1.nom;
    this.p2_nom=p2.nom;
    this.x=0;
    this.y=0;
    this.x1=0;
    this.y1=0;
    this.p1_x=0;
    this.p1_y=0;
    this.p2_x=0;
    this.p2_y=0;
    this.p1_port_nom='';
    this.p2_port_nom='';
    /*
        On compare les positions sur y des 2 machines. Cela permet d'afficher le port en haut ou en bas en fonction de l'emplacement des machines
    */
    if(p1.y>p2.y){
        //this.x=p1.x+p1.width/2-20+p1.nbportH*20;
        this.x=p1.x+p1.width/2-20+port1*20;
        this.y=p1.y-30;
        //this.x1=p2.width/2-20+p2.x+p2.nbportB*20;
        this.x1=p2.width/2-20+p2.x+port2*20;
        this.y1=p2.height+p2.y+10;
        //this.p1_x=p1.x+p1.width/2-20+p1.nbportH*20-5;
        this.p1_x=p1.x+p1.width/2-20+port1*20-5;
        this.p1_y=p1.y-20;
        //this.p2_x=p2.width/2-20+p2.x+p2.nbportB*20-5;
        this.p2_x=p2.width/2-20+p2.x+port2*20-5;
        this.p2_y=p2.height+p2.y+10;
        this.p1_port_nom='['+port1+']';
        this.p2_port_nom='['+port2+']';
        p1.nbportH++;
        p2.nbportB++;
    }else{
        //this.x=p1.width/2-20+p1.x+p1.nbportB*20;
        this.x=p1.width/2-20+p1.x+port1*20;
        this.y=p1.height+p1.y+10;
        //this.x1=p2.x+p2.width/2-20+p2.nbportH*20;
        this.x1=p2.x+p2.width/2-20+port2*20;
        this.y1=p2.y-30;
        //this.p1_x=p1.width/2-20+p1.x+p1.nbportB*20-5;
        this.p1_x=p1.width/2-20+p1.x+port1*20-5;
        this.p1_y=p1.height+p1.y+10;
        //this.p2_x=p2.x+p2.width/2-20+p2.nbportH*20-5;
        this.p2_x=p2.x+p2.width/2-20+port2*20-5;
        this.p2_y=p2.y-20;
        this.p1_port_nom='['+port1+']';
        this.p2_port_nom='['+port2+']';
        p1.nbportB++;
        p2.nbportH++;
    }
}

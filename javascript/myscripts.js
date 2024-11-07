var app = 
{
    playing : false,
    queue : [],

    init: function(document)
    {  
        var that = this;

        $( "#play" ).on( "click", function() 
        {
            if(that.playing)
            {
                that.playing = false;
                console.log("pause");
                $("#play .icon").attr("src","icons/play.png");
            }
            else
            {
                that.playing = true;
                console.log("play");
                that.play();
                $("#play .icon").attr("src","icons/pause.png");
            }
        } );
    },

    push : function(a,b)
    {
        var that = this;

        that.queue.push({a,b});
    },

    play : function()
    {
        var that = this;

        var elem = that.queue[0];

        $("#content").prepend("<div class='textA'>" + elem.a + "</div>");
        $("#content").prepend("<div class='textB'>" + elem.b + "</div>");

        that.queue.shift();

        console.log("playing",elem,that.queue);

        that.queue.push(elem);

        setTimeout(function()
        {
            if(that.playing)
            {
                that.play();
            }
        },5000);

    }
}

$( document ).ready(function() 
{
    app.init(document);

    app.push("It was the best sandcastle he had ever seen.","C'était le meilleur château de sable qu'il ait jamais vu.");
    app.push("Red is greener than purple, for sure.","Le rouge est plus vert que le violet, c’est sûr.");
    app.push("Buried deep in the snow, he hoped his batteries were fresh in his avalanche beacon.","Enfoui profondément dans la neige, il espérait que ses batteries étaient neuves dans sa balise d'avalanche.");
    app.push("The thick foliage and intertwined vines made the hike nearly impossible.","Le feuillage épais et les vignes entrelacées rendaient la randonnée presque impossible.");
    app.push("Little Red Riding Hood decided to wear orange today.","Le Petit Chaperon Rouge a décidé de porter du orange aujourd'hui.");
    app.push("Mothers spend months of their lives waiting on their children.","Les mères passent des mois de leur vie à attendre leurs enfants.");
    app.push("She was only made the society president because she can whistle with her toes.","Elle n’a été nommée présidente de la société que parce qu’elle sait siffler avec ses orteils.");
    app.push("Improve your goldfish's physical fitness by getting him a bicycle.","Améliorez la forme physique de votre poisson rouge en lui offrant un vélo.");
    app.push("There were a lot of paintings of monkeys waving bamboo sticks in the gallery.","Il y avait beaucoup de peintures de singes agitant des bâtons de bambou dans la galerie.");
    app.push("Yeah, I think it's a good environment for learning English.","Oui, je pense que c'est un bon environnement pour apprendre l'anglais.");
    app.push("He always wore his sunglasses at night.","La nuit, il portait toujours ses lunettes de soleil.");
    app.push("So long and thanks for the fish.","Au revoir et merci pour le poisson.");
    app.push("He stepped gingerly onto the bridge knowing that enchantment awaited on the other side.","Il marcha avec précaution sur le pont, sachant que l'enchantement l'attendait de l'autre côté.");
    app.push("The quick brown fox jumps over the lazy dog.","Le renard brun rapide saute par-dessus le chien paresseux.");
    app.push("He set out for a short walk, but now all he could see were mangroves and water were for miles.","Il partit pour une courte promenade, mais maintenant tout ce qu'il pouvait voir c'était des mangroves et de l'eau à des kilomètres à la ronde.");
    app.push("Normal activities took extraordinary amounts of concentration at the high altitude.","Les activités normales demandaient une concentration extraordinaire à haute altitude.");
    app.push("Jason lived his life by the motto : Anything worth doing is worth doing poorly.","Jason a vécu sa vie selon la devise : tout ce qui vaut la peine d'être fait vaut la peine d'être mal fait.");
    app.push("When nobody is around, the trees gossip about the people who have walked under them.","Quand il n'y a personne autour, les arbres racontent des ragots sur les gens qui ont marché sous eux.");
    app.push("Watching the geriatric men’s softball team brought back memories of 3 yr olds playing t-ball.","Regarder l’équipe de softball masculine gériatrique m’a rappelé des souvenirs d’enfants de 3 ans jouant au t-ball.");
    app.push("He would only survive if he kept the fire going and he could hear thunder in the distance.","Il ne survivrait que s'il entretenait le feu et s'il pouvait entendre le tonnerre au loin.");
    app.push("The sight of his goatee made me want to run and hide under my sister-in-law's bed.","La vue de sa barbiche m'a donné envie de courir me cacher sous le lit de ma belle-sœur.");
    app.push("There was no telling what thoughts would come from the machine.","On ne pouvait pas savoir quelles pensées allaient sortir de la machine.");
    app.push("I'd rather be a bird than a fish.","Je préférerais être un oiseau qu'un poisson.");
    app.push("Nobody questions who built the pyramids in Mexico.","Personne ne se demande qui a construit les pyramides au Mexique.");
    app.push("There's a message for you if you look up.","Il y a un message pour vous si vous levez les yeux.");
    app.push("It was difficult for Mary to admit that most of her workout consisted of exercising poor judgment","Il était difficile pour Mary d’admettre que la majeure partie de son entraînement consistait à faire preuve d’un mauvais jugement.");
    app.push("Waffles are always better without fire ants and fleas.","Les gaufres sont toujours meilleures sans fourmis de feu ni puces.");
    app.push("25 years later, she still regretted that specific moment.","25 ans plus tard, elle regrette encore ce moment précis.");
    app.push("Martha came to the conclusion that shake weights are a great gift for any occasion.","Martha est arrivée à la conclusion que les poids shake sont un excellent cadeau pour toute occasion.");
    app.push("You bite up because of your lower jaw.","Vous mordez à cause de votre mâchoire inférieure.");
    app.push("He was sure the Devil created red sparkly glitter.","Il était sûr que le Diable avait créé des paillettes rouges étincelantes.");
    app.push("It took me too long to realize that the ceiling hadn't been painted to look like the sky.","Il m’a fallu trop de temps pour réaliser que le plafond n’avait pas été peint pour ressembler au ciel.");
    app.push("Sometimes it is better to just walk away from things and go back to them later when you’re in a better frame of mind.","Parfois, il est préférable de simplement s’éloigner des choses et d’y revenir plus tard, lorsque vous êtes dans un meilleur état d’esprit.");
    app.push("I used to live in my neighbor's fishpond, but the aesthetic wasn't to my taste.","J'habitais dans l'étang à poissons de mon voisin, mais l'esthétique n'était pas à mon goût.");
    app.push("The body piercing didn't go exactly as he expected.","Le piercing ne s'est pas déroulé exactement comme il l'avait prévu.");
    app.push("Sometimes you have to just give up and win by cheating.","Parfois, il faut simplement abandonner et gagner en trichant.");
    app.push("Bill ran from the giraffe toward the dolphin.","Bill a couru de la girafe vers le dauphin.");
    app.push("Tuesdays are free if you bring a gnome costume.","Les mardis sont gratuits si vous apportez un costume de gnome.");
    app.push("The secret code they created made no sense, even to them.","Le code secret qu’ils avaient créé n’avait aucun sens, même pour eux.");
    app.push("On a scale from one to ten, what's your favorite flavor of random grammar?","Sur une échelle de un à dix, quelle est votre saveur préférée de grammaire aléatoire ?");
    app.push("He was willing to find the depths of the rabbit hole in order to be with her.","Il était prêt à trouver les profondeurs du terrier du lapin pour être avec elle.");
    app.push("A glittering gem is not enough.","Un joyau scintillant ne suffit pas.");
    app.push("Stop waiting for exceptional things to just happen.","Arrêtez d’attendre que des choses exceptionnelles se produisent.");
    app.push("The father handed each child a roadmap at the beginning of the 2-day road trip and explained it was so they could find their way home.","Au début du voyage de deux jours, le père a remis à chaque enfant une feuille de route et lui a expliqué que c'était pour qu'ils puissent retrouver le chemin du retour.");
    app.push("I'm not a party animal, but I do like animal parties.","Je ne suis pas un fêtard, mais j'aime les fêtes d'animaux.");
    app.push("I would be delighted if the sea were full of cucumber juice.","Je serais ravi si la mer était pleine de jus de concombre.");
    app.push("He walked into the basement with the horror movie from the night before playing in his head.","Il est entré dans le sous-sol avec le film d'horreur de la veille qui tournait dans sa tête.");
    app.push("When confronted with a rotary dial phone the teenager was perplexed.","Confronté à un téléphone à cadran rotatif, l'adolescent était perplexe.");
    app.push("They're playing the piano while flying in the plane.","Ils jouent du piano pendant le vol en avion.");
    app.push("The tattered work gloves speak of the many hours of hard labor he endured throughout his life.","Les gants de travail en lambeaux témoignent des nombreuses heures de dur labeur qu'il a endurées tout au long de sa vie.");
    app.push("That would be the first time.","Ce serait la première fois.");

});



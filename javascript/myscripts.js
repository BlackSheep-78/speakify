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
                $("#play .icon").attr("src","icons/pause.png");
            }
        } );
    },

    push : function(a,b)
    {
        var that = this;

        that.queue.push({a,b});
    }
}


$( document ).ready(function() 
{
    app.init(document);

    app.push("It was the best sandcastle he had ever seen.","C'était le meilleur château de sable qu'il ait jamais vu.");
    app.push("Red is greener than purple, for sure.","...");
    app.push("Buried deep in the snow, he hoped his batteries were fresh in his avalanche beacon.","...");
    app.push("The thick foliage and intertwined vines made the hike nearly impossible.","...");
    app.push("Little Red Riding Hood decided to wear orange today.","...");
    app.push("Mothers spend months of their lives waiting on their children.","...");
    app.push("She was only made the society president because she can whistle with her toes.","...");
    app.push("Improve your goldfish's physical fitness by getting him a bicycle.","...");
    app.push("There were a lot of paintings of monkeys waving bamboo sticks in the gallery.","...");
    app.push("Yeah, I think it's a good environment for learning English.","...");
    app.push("He always wore his sunglasses at night.","...");
    app.push("So long and thanks for the fish.","...");
    app.push("He stepped gingerly onto the bridge knowing that enchantment awaited on the other side.","...");
    app.push("The quick brown fox jumps over the lazy dog.","...");
    app.push("He set out for a short walk, but now all he could see were mangroves and water were for miles.","...");
    app.push("Normal activities took extraordinary amounts of concentration at the high altitude.","...");
    app.push("Jason lived his life by the motto : Anything worth doing is worth doing poorly.","...");
    app.push("When nobody is around, the trees gossip about the people who have walked under them.","...");
    app.push("Watching the geriatric men’s softball team brought back memories of 3 yr olds playing t-ball.","...");
    app.push("He would only survive if he kept the fire going and he could hear thunder in the distance.","...");
    app.push("The sight of his goatee made me want to run and hide under my sister-in-law's bed.","...");
    app.push("There was no telling what thoughts would come from the machine.","...");
    app.push("I'd rather be a bird than a fish.","...");
    app.push("Nobody questions who built the pyramids in Mexico.","...");
    app.push("There's a message for you if you look up.","...");
    app.push("It was difficult for Mary to admit that most of her workout consisted of exercising poor judgment.","...");
    app.push("Waffles are always better without fire ants and fleas.","...");
    app.push("25 years later, she still regretted that specific moment.","...");
    app.push("Martha came to the conclusion that shake weights are a great gift for any occasion.","...");
    app.push("You bite up because of your lower jaw.","...");
    app.push("He was sure the Devil created red sparkly glitter.","...");
    app.push("It took me too long to realize that the ceiling hadn't been painted to look like the sky.","...");
    app.push("Sometimes it is better to just walk away from things and go back to them later when you’re in a better frame of mind.","...");
    app.push("I used to live in my neighbor's fishpond, but the aesthetic wasn't to my taste.","...");
    app.push("The body piercing didn't go exactly as he expected.","...");
    app.push("Sometimes you have to just give up and win by cheating.","...");
    app.push("Bill ran from the giraffe toward the dolphin.","...");
    app.push("Tuesdays are free if you bring a gnome costume.","...");
    app.push("The secret code they created made no sense, even to them.","...");
    app.push("On a scale from one to ten, what's your favorite flavor of random grammar?","...");
    app.push("He was willing to find the depths of the rabbit hole in order to be with her.","...");
    app.push("A glittering gem is not enough.","...");
    app.push("Stop waiting for exceptional things to just happen.","...");
    app.push("The father handed each child a roadmap at the beginning of the 2-day road trip and explained it was so they could find their way home.","...");
    app.push("I'm not a party animal, but I do like animal parties.","...");
    app.push("I would be delighted if the sea were full of cucumber juice.","...");
    app.push("He walked into the basement with the horror movie from the night before playing in his head.","...");
    app.push("When confronted with a rotary dial phone the teenager was perplexed.","...");
    app.push("They're playing the piano while flying in the plane.","...");
    app.push("The tattered work gloves speak of the many hours of hard labor he endured throughout his life.","...");
});



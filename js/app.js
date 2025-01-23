var app = 
{
    playing  : false,
    data     : { group : {} },
    playlist : [],
    elements : {},
    template : {},
    schema   : {},

    init: function(document)
    {  
        let that = this;
        that.elements["#content"] = document.getElementById("content");
        that.elements["#content"].innerHTML = "";

        $("#b1").on('click',function() { });
        $("#b2").on('click',function() { });
        $("#b3").on('click',function() { });
        $("#b5").on('click',function() { });
        $("#b6").on('click',function() { that.play(); });
    },

    first: function(obj)
    {
        for (const key in obj) 
        {
            if (obj.hasOwnProperty(key)) 
            { 
                return key;
            }
        }
    },

    load: function(arguments,callback)
    {
        fetch(arguments.url) // URL to fetch data from
        .then(response => 
        {
            if (!response.ok) 
            {
                throw new Error('Network response was not ok');
            }

            if(arguments.dataType && arguments.dataType == "text")
            {
                return response.text();
            }

            return response.json(); // Parse JSON from the response
        })
        .then(data => callback(data)) // Handle the JSON data
        .catch(error => 
        {
            console.error('There was a problem with the fetch operation:', error);
        });
    },

    loadTranslations : function(callback)
    {
        let that     = this;
        let group    = that.data.group;
        let playlist = that.playlist;
        
        that.ajax("json=true&get=sentences",function(data)
        {
            console.log("** Translations **");
            console.log(data);
            console.log(data.translation.pairs);

            for(let i = 0; i < data.translation.pairs.length; i++)
            {
                let pair  = data.translation.pairs[i];

                if(typeof group[pair['id1']] == 'undefined')
                {
                    group[pair['id1']] = pair;
                    playlist.push(group[pair['id1']]);
                }
            } 

            console.log(group);
            console.log(playlist);

            callback();
        }); 
    },

    loadHtmlTemplate: function(url,callback)
    {
        let that = this;

        if(that.template[url])
        {
            if(callback)
            {
                callback(that.template[url]);
            }
            
            return that.template[url];
        }

        that.load({'url':url,'dataType':'text'},function(data)
        {
            that.template[url] = data;

            if(callback)
            {
                callback(that.template[url]);
            }
        });
    },

    loadSchema: function(url,callback)
    {
        let that = this;

        if(that.schema[url])
        {
            if(callback)
            {
                callback(that.schema[url]);
            }
            
            return that.schema[url];
        }

        that.load({'url':url,'dataType':'json'},function(data)
        {
            that.schema[url] = data;

            if(callback)
            {
                callback(that.schema[url]);
            }
        });
    },

    dataPlusHtmlToElement: function(type,data,html)
    {
        console.log(data);

        let that = this;

        const template = document.createElement('template');
        template.innerHTML = html;

        let element = template.content.firstElementChild;

        let sentence = element.getElementsByClassName("sentence");
        let first = that.first(data.sentences);
        sentence[0].innerHTML = data.sentences[first].sentence.text;

        return element;
    },

    addTranslationBlockToInterface: function(index)
    {
        /*
        let that = this;

        if(typeof index == "undefined") { index = 0; }
        let data = that.playlist[index];
        if(typeof data == "undefined") { return; }

        let html = that.loadHtmlTemplate('html/template/translation.html');

        that.playlist[index]['element'] = that.dataPlusHtmlToElement('translation',data,html);

        console.log(that.playlist[index]);

        that.elements['#content'].append(that.playlist[index]['element']);

        index += 1;
        setTimeout(function()
        { 
            that.addTranslationBlockToInterface(index);

        },1000);
        */
    },

    play : function()
    {
        let that = this;

        that.loadSchema('json/schema1.json',function(html)
        {
            that.loadHtmlTemplate('html/template/translation.html',function(html)
            {
                that.loadTranslations(function()
                {
                    that.addTranslationBlockToInterface();
                });
            });
        }); 
    },

    run: function()
    {
        var schema = [{language:1,quantity:4},{language:2,quantity:3},{language:3,quantity:2},{language:2,quantity:2}];

        this.loop(schema);
    },

    loop: function(schema,list,iteration)
    {
        var that = this;

        if(typeof(iteration) === 'undefined') { iteration = 0; }

        var elem    = that.queue[0];
        var content = $("#content");

        if(iteration === 0)
        {
            $(content).empty();

            var list = [];
            var tmp  = {};

            for(var i = 0; i < elem.length; i++) 
            {
                tmp[elem[i].language] = elem[i];
            }

            for(var i = 0; i < schema.length; i++) 
            {

                if(typeof schema[i].events === 'undefined') { schema[i].events = {}; }

                schema[i].block = $("<div class='textA'>" + tmp[schema[i].language].text + "</div>");


                if(tmp[schema[i].language].id)
                {
                    schema[i].audio = $("<audio src='audio/" + tmp[schema[i].language].id + ".wav'></audio>");
                }

                schema[i].progress = $("<div class='progress'></div>");

                for(var j = 0; j < schema[i].quantity; j++) 
                {
                    list.push({block:i});
                }

                $(schema[i].block).hide().fadeIn().appendTo(content);

                if(schema[i].audio)
                {
                    $(schema[i].audio).hide().fadeIn().appendTo(content);
                }

                $(schema[i].progress).hide().fadeIn().appendTo(content);
            };

            console.log(list);

        }

        var block = list[iteration].block;

        if(!('ended' in schema[block].events))
        {
            schema[block].events['ended'] = true;

            schema[block].audio[0].addEventListener('ended', function() 
            {
                iteration = iteration + 1;

                that.loop(schema,list,iteration);
            }, false);
        }

        /*
            schema[block].audio[0].addEventListener("canplay",function()
            {
                console.log("Duration:" + audioA[0].duration + " seconds");
                console.log("Source:" + audioA[0].src);
            });
        */

        schema[block].progressInitialLenght = $(schema[block].progress).width();
        schema[block].progressFinalLenght   = $(schema[block].block).outerWidth() - schema[block].progressInitialLenght;
        

        if(!('timeupdate' in schema[block].events))
        {
            schema[block].events['timeupdate'] = true;

            schema[block].audio[0].addEventListener("timeupdate",function()
            {
                var currentWidth = schema[block].progressInitialLenght + ( schema[block].progressFinalLenght * (schema[block].audio[0].currentTime / schema[block].audio[0].duration));
                $(schema[block].progress).width(currentWidth + "px");
            });
        }


        console.log("iteration",iteration);
        console.log("block",block);
        schema[block].audio[0].play();

        //that.queue.shift();
        //that.queue.push(elem);
    },

    ajax : function(args,callback)
    {
        var that = this;

        $.get( "http://localhost/translate/?" + args, function( data ) 
        {
            if(callback)
            {
                callback(JSON.parse(data))
            }

        });
    }
}

$(document).ready(function()
{
    app.init(document);
});



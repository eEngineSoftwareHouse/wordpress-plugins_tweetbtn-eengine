(function() {
    console.log('tweetbtn.js');
    tinymce.create('tinymce.plugins.tweetbtn', {
        init : function(ed, url) {
            ed.addButton('tweetbtn', {
                title : 'Tweet',
                image : url+'/tweetbtn.png',
                onclick : function() {
                    var tweet = ed.selection.getContent({format : 'text'});
                    ed.execCommand('mceInsertContent', false, '[tweet]'+tweet+'[/tweet]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname : "Tweet",
                author : 'sciolkowski',
                authorurl : 'http://eengine.pl',
                version : "0.1"
            };
        }
    });
    tinymce.PluginManager.add('tweetbtn', tinymce.plugins.tweetbtn);
})();
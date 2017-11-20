(function($) {
    $.entwine(function($) {
        $('input.youtube').entwine({
            onmatch: function() {
                if (this.val().length > 0) {
                    getYouTubeSnippet(this);
                }
            },
            onkeyup: function() {
                getYouTubeSnippet(this);
            }
        });
    });
}(jQuery));

function getYouTubeSnippet(element) {
    var api_data = element.data('apidata');
	var api_key = element.data('apikey');
    var youtube_id = element.val().match(/[a-zA-Z0-9_-]{11}/);

    if (!youtube_id) {
        element.parent().removeClass('youtube-active');
        return false;
    }

    if (api_data) {
        return showYouTubeSnippet(element, api_data);
    }

    var yt = JSON.parse(localStorage.getItem('youtube-' + youtube_id[0]));
	if (yt) {
        return showYouTubeSnippet(element, yt);
    }

    var request = gapi.client.youtube.videos.list({
        id: youtube_id[0],
        part: 'snippet,statistics,contentDetails',
        key: api_key
    });

    request.execute(function(response) {
        var yt = response.items[0];

        if(typeof yt !== 'undefined') {
            localStorage.setItem('youtube-' + youtube_id[0], JSON.stringify(yt));
            return showYouTubeSnippet(element, yt);
        }
    });
}

function showYouTubeSnippet(element, yt) {
    var infobox = element.parent().find('.youtube-info');

    element.parent().addClass('youtube-active');
    infobox.find('.youtube-info-title a').text(yt.snippet.title);
    infobox.find('.youtube-info-title a').attr('href', 'https://www.youtube.com/watch?v=' + yt.id);
    var more = ['<strong>' + yt.snippet.channelTitle + '</strong>'];
    if (typeof yt.contentDetails !== 'undefined' && typeof yt.contentDetails.duration !== 'undefined') {
        more.push('Duration: ' + parseYouTubeDuration(yt.contentDetails.duration));
    }
    if (typeof yt.statistics !== 'undefined' && typeof yt.statistics.viewCount !== 'undefined') {
        more.push('Views: ' + parseInt(yt.statistics.viewCount).toLocaleString());
    }
    infobox.find('.youtube-info-more').html(more.join(' &middot; '));
    infobox.find('.youtube-info-thumb').attr('src', yt.snippet.thumbnails.default.url);

    return true;
}

function googleApiClientReady() {
    gapi.client.load('youtube', 'v3', function() {
        getYouTubeSnippet(jQuery('input.youtube'));
    });
}

function parseYouTubeDuration(e){var n=e.replace(/D|H|M/g,":").replace(/P|T|S/g,"").split(":");if(1==n.length)2!=n[0].length&&(n[0]="0"+n[0]),n[0]="0:"+n[0];else for(var r=1,l=n.length-1;l>=r;r++)2!=n[r].length&&(n[r]="0"+n[r]);return n.join(":")}

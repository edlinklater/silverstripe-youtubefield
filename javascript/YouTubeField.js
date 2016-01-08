(function($) {
	$(document).ready(function () {

		$('input.youtube').keyup(function(){
			getYouTubeSnippet($(this));
		});

	});
}(jQuery));

function getYouTubeSnippet(element) {
	var infobox = element.parent().find('.youtube-info');
	var api_key = element.data('apikey');

	var regex = /[a-zA-Z0-9_-]{11}/;
	var youtube_id = element.val().match(regex);

	if(youtube_id) {
		var request = gapi.client.youtube.videos.list({
			id: youtube_id[0],
			part: 'snippet,statistics,contentDetails',
			key: api_key
		});

		request.execute(function(response) {
			var yt = response.items[0];

			if(typeof yt !== 'undefined') {
				element.parent().addClass('youtube-active');
				infobox.find('.youtube-info-title a').text(yt.snippet.title);
				infobox.find('.youtube-info-title a').attr('href', 'https://www.youtube.com/watch?v=' + yt.id);
				infobox.find('.youtube-info-more').html([
					'<strong>' + yt.snippet.channelTitle + '</strong>',
					'Duration: ' + parseYouTubeDuration(yt.contentDetails.duration),
					'Views: ' + parseInt(yt.statistics.viewCount).toLocaleString()
				].join(' &middot; '));
				infobox.find('.youtube-info-thumb').attr('src', yt.snippet.thumbnails.default.url);
			}
		});
	} else {
		element.parent().removeClass('youtube-active');
	}
}

function googleApiClientReady() {
	gapi.client.load('youtube', 'v3', function() {
		getYouTubeSnippet(jQuery('input.youtube'));
	});
}

function parseYouTubeDuration(e){var n=e.replace(/D|H|M/g,":").replace(/P|T|S/g,"").split(":");if(1==n.length)2!=n[0].length&&(n[0]="0"+n[0]),n[0]="0:"+n[0];else for(var r=1,l=n.length-1;l>=r;r++)2!=n[r].length&&(n[r]="0"+n[r]);return n.join(":")}
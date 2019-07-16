// After the API loads, call a function to enable the search box.
function handleAPILoaded() {
  $('#search-button').attr('disabled', false);
}
// Search for a specified string.
// function key(){
//   var key = $('#query').val();
//   if (key == ''){
//       alert('Bạn phải nhập đầy đủ thông tin!');
//   }
//   else{
//     $.post('view/validate.php',{postkey:key},
//       function(data){
//         $('#result').html(data);
//       }
//     );
//   }
// }
function search() {
  // key();
  var token;
  var dem=0;
  var arr2=[];
  if($("#checkbox").prop("checked") == true){
    var q = "allintitle: "+$('#query').val();
  }
  else{
    var q = $('#query').val();
  }
  if($("#order").prop("checked") == true){
    var order = "viewCount";
  }
  else{
    var order = "date";
  }
  if($("#videoDuration").prop("checked") == true){
    var videoDuration = "long";
  }
  else{
    var videoDuration = "any";
  }
  $(".tach").detach();
  var p = $('#publish').val();
  var p1 = $('#publish1').val(); 
  var location1 = $('#location').val(); 
  var locationRadius1 = $('#locationRadius').val();
  var request = gapi.client.youtube.search.list({
    'location': location1,
    'locationRadius': locationRadius1,
    'part': 'snippet',
    'videoDuration':videoDuration,
    'q': q,
    'type': 'video',
    'publishedAfter':p,
    'publishedBefore':p1,
    'order': order,
    maxResults: 50,
  });
  request.execute(function(responseid) {//id
    // arr2.push({id:responseid.items});
    // console.log(responseid);
    // $.each(responseid,function(key,value){
    //   console.log(key.items);
    // });
    var m=0;
    for(i=0;i<responseid.items.length;i++){
      setTimeout(2000);
          // console.log(responseid.result.items[i].id.videoId);

      var request1 = gapi.client.youtube.videos.list({
        part: 'snippet,contentDetails,statistics',
        id:responseid.result.items[i].id.videoId,
      });
      request1.execute(function(response) { //video
        // console.log(response.items[0].statistics.viewCount);
        // console.log(response);
        setTimeout(2000);
        var request2 = gapi.client.youtube.channels.list({
          part: 'snippet,statistics',
          id:response.items[0].snippet.channelId,
        });
        setTimeout(2000);
        request2.execute(function(responsechannel) {//channel
          setTimeout(2000);
          arr2.push({id:responseid.items[m].id.videoId,view:response.items[0].statistics.viewCount});
          // console.log(response.items[0].statistics.viewCount);
          // console.log(arr2);
            if (responsechannel.items[0].statistics.subscriberCount<5000) {
              $("#table").append("<tr class='tach'><td style='text-align:center;'>"+dem+"</td><td><img style='width: 200px' src='"+response.items[0].snippet.thumbnails.high.url+"' class='img-responsive'></td><td>"+response.items[0].snippet.title+"<br/><b style='color:red;font-size: 30px;'>Sub: "+responsechannel.items[0].statistics.subscriberCount+"</b> <br/><b style='color:red;font-size: 25px;'>View kênh: "+responsechannel.items[0].statistics.viewCount+"</b><br/><b>Kênh:</b> "+responsechannel.items[0].snippet.publishedAt+"<br/><b>Tổng video kênh:</b> "+responsechannel.items[0].statistics.videoCount+"</td><td><b>View:</b><b style='color:red;font-size: 20px;'> "+response.result.items[0].statistics.viewCount+"</b><br/><b>Like:</b> "+response.result.items[0].statistics.likeCount+"<br/><b>Dislike:</b> "+response.result.items[0].statistics.dislikeCount+"<br/><b>Comment:</b> "+response.result.items[0].statistics.commentCount+"</td><td><b>Publish at:</b> "+response.items[0].snippet.publishedAt+"<br/><b>Duration:</b> "+response.items[0].contentDetails.duration+"</td><td><input type='text' value='"+response.items[0].id+"' id='id' readonly><br/><a href='https://www.youtube.com/watch?v="+response.items[0].id+"' target='_blank'>Watch Video</a><br/><a href='https://www.youtube.com/channel/"+response.items[0].snippet.channelId+"/videos' target='_blank'>Watch Channel</a></td></tr>");
            } else{
              $("#table").append("<tr class='tach'><td style='text-align:center;'>"+dem+"</td><td><img style='width: 200px' src='"+response.items[0].snippet.thumbnails.high.url+"' class='img-responsive'></td><td>"+response.items[0].snippet.title+"<br/><b>Sub:</b> "+responsechannel.items[0].statistics.subscriberCount+"<br/><b>View kênh:</b> "+responsechannel.items[0].statistics.viewCount+"<br/><b>Kênh:</b> "+responsechannel.items[0].snippet.publishedAt+"<br/><b>Tổng video kênh:</b> "+responsechannel.items[0].statistics.videoCount+"</td><td><b>View:</b> "+response.result.items[0].statistics.viewCount+"<br/><b>Like:</b> "+response.result.items[0].statistics.likeCount+"<br/><b>Dislike:</b> "+response.result.items[0].statistics.dislikeCount+"<br/><b>Comment:</b> "+response.result.items[0].statistics.commentCount+"</td><td><b>Publish at:</b> "+response.items[0].snippet.publishedAt+"<br/><b>Duration:</b> "+response.items[0].contentDetails.duration+"</td><td><input type='text' value='"+response.items[0].id+"' id='id' readonly><br/><a href='https://www.youtube.com/watch?v="+response.items[0].id+"' target='_blank'>Watch Video</a><br/><a href='https://www.youtube.com/channel/"+response.items[0].snippet.channelId+"/videos' target='_blank'>Watch Channel</a></td></tr>");
            }
            
            dem++;
            m++;
          });
      });
      // console.log(i);
    }
    token = responseid.nextPageToken;
    get_nextpage();
    
    // console.log(token);
    // console.log(responseid);
  });

  function get_nextpage(){
      var pagetoken = gapi.client.youtube.search.list({
        'location': location1,
        'locationRadius': locationRadius1,
        'part': 'snippet',
        'videoDuration':videoDuration,
        'q': q,
        'type': 'video',
        'publishedAfter':p,
        'publishedBefore':p1,
        'order': order,
        'pageToken': token,
        maxResults: 50
      });
      pagetoken.execute(function(responsetoken) {
        for(i=0;i<responsetoken.items.length;i++){
          var request1 = gapi.client.youtube.videos.list({
            part: 'snippet,contentDetails,statistics',
            id:responsetoken.result.items[i].id.videoId,
          });
          request1.execute(function(response) { //video
            var request2 = gapi.client.youtube.channels.list({
              part: 'snippet,statistics',
              id:response.items[0].snippet.channelId,
            });
            request2.execute(function(responsechannel) { //channel
                if (responsechannel.items[0].statistics.subscriberCount<5000) {
                 $("#table").append("<tr class='tach'><td style='text-align:center;'>"+dem+"</td><td><img style='width: 200px' src='"+response.items[0].snippet.thumbnails.high.url+"' class='img-responsive'></td><td>"+response.items[0].snippet.title+"<br/><b style='color:red;font-size: 30px;'>Sub: "+responsechannel.items[0].statistics.subscriberCount+"</b> <br/><b style='color:red;font-size: 25px;'>View kênh: "+responsechannel.items[0].statistics.viewCount+"</b><br/><b>Kênh:</b> "+responsechannel.items[0].snippet.publishedAt+"<br/><b>Tổng video kênh:</b> "+responsechannel.items[0].statistics.videoCount+"</td><td><b>View:</b><b style='color:red;font-size: 20px;'> "+response.result.items[0].statistics.viewCount+"</b><br/><b>Like:</b> "+response.result.items[0].statistics.likeCount+"<br/><b>Dislike:</b> "+response.result.items[0].statistics.dislikeCount+"<br/><b>Comment:</b> "+response.result.items[0].statistics.commentCount+"</td><td><b>Publish at:</b> "+response.items[0].snippet.publishedAt+"<br/><b>Duration:</b> "+response.items[0].contentDetails.duration+"</td><td><input type='text' value='"+response.items[0].id+"' id='id' readonly><br/><a href='https://www.youtube.com/watch?v="+response.items[0].id+"' target='_blank'>Watch Video</a><br/><a href='https://www.youtube.com/channel/"+response.items[0].snippet.channelId+"/videos' target='_blank'>Watch Channel</a></td></tr>");
                } else{
                  if (responsechannel.items[0].statistics.viewCount!=0) {
                    $("#table").append("<tr class='tach'><td style='text-align:center;'>"+dem+"</td><td><img style='width: 200px' src='"+response.items[0].snippet.thumbnails.high.url+"' class='img-responsive'></td><td>"+response.items[0].snippet.title+"<br/><b>Sub:</b> "+responsechannel.items[0].statistics.subscriberCount+"<br/><b>View kênh:</b> "+responsechannel.items[0].statistics.viewCount+"<br/><b>Kênh:</b> "+responsechannel.items[0].snippet.publishedAt+"<br/><b>Tổng video kênh:</b> "+responsechannel.items[0].statistics.videoCount+"</td><td><b>View:</b> "+response.result.items[0].statistics.viewCount+"<br/><b>Like:</b> "+response.result.items[0].statistics.likeCount+"<br/><b>Dislike:</b> "+response.result.items[0].statistics.dislikeCount+"<br/><b>Comment:</b> "+response.result.items[0].statistics.commentCount+"</td><td><b>Publish at:</b> "+response.items[0].snippet.publishedAt+"<br/><b>Duration:</b> "+response.items[0].contentDetails.duration+"</td><td><input type='text' value='"+response.items[0].id+"' id='id' readonly><br/><a href='https://www.youtube.com/watch?v="+response.items[0].id+"' target='_blank'>Watch Video</a><br/><a href='https://www.youtube.com/channel/"+response.items[0].snippet.channelId+"/videos' target='_blank'>Watch Channel</a></td></tr>");
                  }
                }
              dem++;
              });
          });
        }
        token=responsetoken.nextPageToken;
        var token1="";
        token1=responsetoken.nextPageToken;
        // console.log(responsetoken);
        // console.log(responsetoken.items.length);
        // console.log(token1);
        if (responsetoken.items.length>0 && token1!=undefined) {
          // console.log(token);
          get_nextpage();
        } else{}
      });
  }
}

//get time
// function timenow() {
//   $(".tach").detach();
//   var q1 = "allintitle: a";  
//   var timenow = gapi.client.youtube.search.list({
//     q: q1,
//     order: 'date',
//     maxResults: 50,
//     part: 'snippet',
//     fields:'items/id/videoId',
//   });
//   timenow.execute(function(responsetime) {
//       var timenow1 = gapi.client.youtube.videos.list({
//         part: 'snippet,contentDetails,statistics',
//         id:responsetime.result.items[0].id.videoId,
//       });
//       timenow1.execute(function(responsetime1) {
//         $(".pub").val(responsetime1 .items[0].snippet.publishedAt);
//       });
//   })
// }

// // get time now
// var d = new Date();
// document.getElementById("timenow").innerHTML = d.toUTCString();


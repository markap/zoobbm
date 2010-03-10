function friend(friendId, userId) {
 $.post("/community/friendajax/friend/" + friendId + "/user/" + userId,
  function(response) {
    $("#friend").html(response);
  },
  "text"
  );

}


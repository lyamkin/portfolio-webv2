$("document").ready(function () {
  // ajax request to load details for each project
  // on button click
  // $(".read-more-details").on("click", function () {
  //   let projectId = $(this).data("detailsId");
  //   $(`#detail-list-project-${projectId}`).load("/ajaxgetdetails/" + projectId);
  // });
  // on page load
  $(".project-details").each(function (index, element) {
    let projectId = $(this).data("detailListId");
    $(this).load("/ajaxgetdetails/" + projectId);
  });
  // ajax request to load tools for each project
  $(".project-tools").each(function (index, element) {
    let projectId = $(this).data("toolsId");
    $(this).load("/ajaxgettools/" + projectId);
  });
  // Get the current year for the copyright
  $("#year").text(new Date().getFullYear());
});

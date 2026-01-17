document.addEventListener("DOMContentLoaded", function () {
  const select = document.querySelector("select[name='citysyncai_content_type']");
  if (select) {
    select.addEventListener("change", function () {
      console.log("Content type changed to:", this.value);
    });
  }
});
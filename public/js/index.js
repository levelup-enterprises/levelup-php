/** ----------------------------------------
 ** Get API path
 * -----------------------------------------
 * @param string name & path of script
 * @returns api root path with name
 */
window.getApi = (name) => {
  return "./app/" + name + ".php";
};

$(() => {
  $.post(getApi("submit"), { test: "test" }).done((data) => {
    console.log(data);
  });
});

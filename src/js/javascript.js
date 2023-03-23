const uploadFunction = event => {
    const files = event.target.files
    console.log(files);
    const data = new FormData()
    data.append('file', files[0])
    
    fetch('post.php',{
        method: 'POST',
        body: data
    })
    .then(response => response.json())
    .then(data => {
        console.log(data)
    })
    .catch(error => {
        console.log(error)
    })
}

document.querySelector('#upload').addEventListener('change', event => {
    uploadFunction(event)
  })
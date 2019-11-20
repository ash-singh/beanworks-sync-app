export function CreatePipeline() {
    let apiUrl = 'http://localhost:8888/api/v1/pipeline';

    return new Promise((resolve, reject) => {
        fetch(apiUrl, {
            method: 'POST',
            body: JSON.stringify({}),
            headers: {
                'api-token': sessionStorage.getItem('token')
            }
        })
            .then((response) => response.json())
            .then((res) => {
                resolve(res);
            })
            .catch((error) => {
                reject(error);
            });

    });

}
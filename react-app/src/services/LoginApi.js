export function LoginApi(userData) {
    let apiUrl = 'http://localhost:8888/api/v1/login';

    return new Promise((resolve, reject) => {

        fetch(apiUrl , {
            method: 'POST',
            body: JSON.stringify(userData)
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
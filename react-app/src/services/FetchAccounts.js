export function FetchAccounts() {
    let apiUrl = 'http://localhost:8888/api/v1/accounts';

    return new Promise((resolve, reject) => {


        fetch(apiUrl,{
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
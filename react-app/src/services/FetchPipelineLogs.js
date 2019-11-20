export function FetchPipelineLogs(pipelineId) {
    let apiUrl = 'http://localhost:8888/api/v1/pipeline/logs/' + pipelineId;

    return new Promise((resolve, reject) => {
        fetch(apiUrl, {
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
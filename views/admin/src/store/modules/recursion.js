const actions = {

    // 处理递归
    handle({ }, recursionData) {
        return new Promise(resolve => {
            let list = recursion(recursionData.data, recursionData.map, recursionData.curChildName, recursionData.needChildName)

            resolve(list)
        })
    }
}

function recursion(data, map, curChildName, needChildName) {
    let resList = [];
    for (const index in data) {
        let item = data[index];
        let list = {};

        for (const key in map) {
            list[key] = item[map[key]];
        }
        if (item[curChildName]) {
            let childList = recursion(item[curChildName], map, curChildName, needChildName);
            list[needChildName] = childList;
        }
        resList.push(list);
    }
    return resList;

}

export default {
    namespaced: true,
    actions
}

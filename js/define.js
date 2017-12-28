const db_issue = [
    {
        'id': 1,
        'name': 'unlabeled',
        'color': 'white',
        'span':'<span class="badge badge-light">unlabeled</span>'
    },
    {
        'id': 2,
        'name': 'bug',
        'color': 'white',
        'span':'<span class="badge badge-danger">bug</span>'
    },
    {
        'id': 3,
        'name': 'duplicate',
        'color': 'white',
        'span':'<span class="badge badge-info">duplicate</span>'
    },
    {
        'id': 4,
        'name': 'enhancement',
        'color': 'white',
        'span':'<span class="badge badge-secondary">enhancement</span>'
    },
    {
        'id': 5,
        'name': 'fixed',
        'color': 'white',
        'span':'<span class="badge badge-success">fixed</span>'
    },
    {
        'id': 6,
        'name': 'help wanted',
        'color': '#white',
        'span':'<span class="badge badge-primary">help wanted</span>'
    },
    {
        'id': 7,
        'name': 'invalid',
        'color': '#white',
        'span':'<span class="badge badge-warning">invalid</span>'
    },
    {
        'id': 8,
        'name': 'question',
        'color': 'white',
        'span':'<span class="badge badge-primary">question</span>'
    },
    {
        'id': 9,
        'name': 'wontfix',
        'color': 'white',
        'span':'<span class="badge badge-info">wontfix</span>'
    }
]
const issue_label_id_to_name = {
};
const issue_label_name_to_id = {

};
const issue_label_name_to_color = {
};
const issue_label_id_to_color = {
};
const issue_label_id_to_span = {

};
db_issue.forEach(element => {
    issue_label_id_to_name[element['id'].toString()] = element['name'];
    issue_label_id_to_color[element['id'].toString()] = element['color'];
    issue_label_name_to_color[element['name'].toString()] = element['color'];
    issue_label_name_to_id[element['name'].toString()] = element['id'].toString();
    issue_label_id_to_span[element['id'].toString()] = element['span'].toString();
    // issue_label_id_to_name[element['id'].toString()] = element['name'];
});
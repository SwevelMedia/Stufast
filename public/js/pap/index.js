$.getJSON(`/api/pap`, function (data) {
    var resources = data
        .sort((a, b) => a.pap_id - b.pap_id)
        .map(({
            pap_id,
            title,
            value
        }) => {
            return (`
            <H3>${pap_id}. ${title}</H3>
                <p>${value.replace(/\n/g, "<br />")}</p>
            `);
        });

    $(".data").html(resources);
});
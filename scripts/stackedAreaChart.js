/* global d3 */
/* exported stackedAreaChart */

function stackedAreaChart(id, config) {
    const margin = ({
        top: 20,
        right: 45,
        bottom: 30,
        left: 30
    });
    const height = 500;
    const width = 640;

    const length = Object.entries(config.data)[0][1].x.length;
    const data = [];
    data.columns = Object.keys(config.data).sort((c1, c2) => config.data[c1].y[0] - config.data[c2].y[0]);
    for (let i = 0; i < length; i++) {
        const rowEntry = (data.columns.reduce((previousValue, k, currentIndex) => {
            const v = config.data[k];
            previousValue[k] = v.y[i] - previousValue.currentY;
            previousValue.currentY = v.y[i];
            if (currentIndex === 0) {
                previousValue.date = new Date(v.x[i]);
            }

            return previousValue;
        }, {currentY: 0}));
        delete rowEntry.currentY;
        data.push(rowEntry);
    }
    data.columns = ['date', ...data.columns];
    data.y = 'date';
    const series = d3.stack().keys(data.columns.slice(1))(data);

    const y = d3.scaleLinear()
        .domain([0, d3.max(series, d => d3.max(d, d => d[1]))])
        .nice()
        .range([height - margin.bottom, margin.top]);

    const x = d3.scaleUtc()
        .domain(d3.extent(data, d => d.date))
        .range([margin.left, width - margin.right]);

    const yAxis = g => g
        .attr("transform", `translate(${width - margin.right},0)`)
        .call(d3.axisRight(y))
        .call(g => g.select(".domain").remove());
    const xAxis = g => g
        .attr("transform", `translate(0,${height - margin.bottom})`)
        .call(d3.axisBottom(x).ticks(width / 80)
            .tickFormat(d => `${d.getFullYear()}-${d.getMonth()} ${d.getDate()}`)
            .tickSizeOuter(0));

    const color = d3.scaleOrdinal()
        .domain(data.columns.slice(1))
        .range(d3.schemeCategory10);

    const area = d3.area()
        .x(d => x(d.data.date))
        .y0(d => y(d[0]))
        .y1(d => y(d[1]));

    const svg = d3.select('#' + id).append('svg')
        .attr("viewBox", [0, 0, width, height]);

    svg.append("g")
        .selectAll("path")
        .data(series)
        .join("path")
        .attr("fill", ({
            key
        }) => color(key))
        .attr("d", area)
        .append("title")
        .text(({
            key
        }) => key);

    svg.append("g")
        .call(xAxis);

    svg.append("g")
        .call(yAxis);

    svg.append("text")
        .attr("x", (width / 2))
        .attr("y", (margin.top / 2) + 1)
        .attr("text-anchor", "middle")
        .attr("class", "charts-text")
        .attr("fill", "currentColor")
        .text(config.title);

    svg.selectAll("seriesColor")
        .data(Object.keys(config.data))
        .enter()
        .append("circle")
        .attr("cx", margin.left + 10)
        .attr("cy", (d,i) => 40 + i * 25)
        .attr("r", 7)
        .style("fill", d => color(d));

    svg.selectAll("series")
        .data(Object.keys(config.data))
        .enter()
        .append("text")
        .attr("fill", "currentColor")
        .attr("x", margin.left + 25)
        .attr("y", (d,i) => 45 + i * 25)
        .text(d => d);
}
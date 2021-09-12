# Graphs

This file contains a description of the way graphs are created in dproofreaders.

In case of problems, please post to the
[DP Site Code](http://www.pgdp.net/phpBB3/viewforum.php?f=32) forum at the
[primary DP site](https://www.pgdp.net/c).

## Overview
Graphs are drawn on the browser using a set of functions from
[svgGraphs.js](../scripts/svgGraphs.js) that interact with the
[d3js](https://d3js.org/) library to render
[Scalable Vector Graphics (svg)](https://en.wikipedia.org/wiki/Scalable_Vector_Graphics)
elements. The 4 types of graphs that are supported are
* Bar
* Line
* Pie
* Stacked Area

## Usage

These graphs are configured using a plain old javascript object. To facilitate
creating the javascript to render a graph from PHP, a PHP utility method
[build_svg_graph_inits](../pinc/graph_data.inc) was created. It is a function
that takes an array of arrays representing the graphs to render. Each graph to
render is a PHP array where the entries are

* type - type of graph to be rendered. Either `barLineGraph`, `pieGraph`, or
`stackedAreaGraph`
* id - id of the div on the page to render the graph into
* configuration - PHP array configuring the graph. Expanded on below.

Example Usage:

```php
build_svg_graph_inits([["barLineGraph", "myGraphId", $graphConfig]]);
```

This will give you a string you can use to pass to js_data in the
[output_header](../pinc/theme.inc) function. Something like

```php
"$(function(){barLineGraph('myGraphId', graphConfigEncodedAsJSON)});"
```

### Embedded scenarios

In some cases, you may wish to embed a graph as if it were an image. Examples
include site news items or other cases where you may not be able to output
scripts to draw the graphs. In these scenarios, we recommend you create a
standalone page that renders just the graph, and then embed that page as an
iframe. You may want to style the iFrame to remove any iFrame borders to have a
seamless image like experience.

One such page for example of a page designed for being embedded as an iFrame is
[round_backlog_days.php](../stats/round_backlog_days.php). It can be embedded
in a news item with the following html.

```html
<iframe src="/stats/round_backlog_days.php" style="width:320px;height:205px;border:0;">
```

# Configuration
Each graph type supports a configuration object that allows control of the
appearance of the graph as well as the data to the graph. Each graph type
respects different configuration properties.
## Bar/Line Graph
```typescript
{
  // Width of the graph in pixels. Defaults to 640 if not provided.
  width?: number;
  
  // Height of the graph in pixels. Defaults to 400 if not provided.
  height?: number;
  
  // Width of y axis in pixels. Defaults to 50 if not provided.
  yAxisWidth?: number;

  // Y axis label. Defaults to first series label or legend box for multiple
  // series.
  yAxisLabel?: string;
  
  // Height of x axis in pixels. Defaults to 50 if not provided.
  xAxisHeight?: number;
  
  // Maximum bumber of y axis ticks to show. Defaults to d3js implementation.
  yAxisTickCount?: number;
  
  // Should be set to true when multiple bar series are set in data that share.
  // the same x values;
  groupBars?: boolean;

  // An array of color values for the series to be colored.
  barColors?: string[];

  // Whether a border should be drawn on the bars in the series.
  barBorder?: boolean;

  // Title of the graph, shown at the top center.
  title?: string;

  // Offset to legend box drawn when there are multiple series.
  legendAdjustment?: {
    x: number; // x offset for legend box
    y: number; // y offset for legend box
  };

  // A value indicating whether the y axis label should be drawn at the bottom.
  // Useful for error scenarios.
  bottomLegend?: boolean;

  // Data to render
  data: {
    [seriesTitle: string]: {
      x: string[]; // X axis labels
      y: string[]; // Y axis values, converted to a number
    };
  };
}
```

## Pie Graph
```typescript
  // Width of the graph in pixels. Defaults to 640 if not provided.
  width?: number;
  
  // Height of the graph in pixels. Defaults to 400 if not provided.
  height?: number;
  
  // Title of the graph, shown at the top center.
  title?: string;

  // Offset to legend box drawn when there are multiple series.
  legendAdjustment?: {
    x: number; // x offset for legend box
    y: number; // y offset for legend box
  };

  // Message to show in the case there isn't data to render.
  error?: string;

  // Labels of pie wedges
  labels: string[];

  // Raw values corresponding to each label.
  data: number[];
}
```

## Stacked Area
```typescript
{
  // Y axis label. Defaults to first series label or legend box for multiple
  // series.
  yAxisLabel?: string;
  
  // Title of the graph, shown at the top center.
  title?: string;

  // Offset to legend box drawn when there are multiple series.
  legendAdjustment?: {
    x: number; // x offset for legend box
    y: number; // y offset for legend box
  };

  // Data to render
  data: {
    [seriesTitle: string]: {
      x: string[]; // X axis labels
      y: string[]; // Y axis values, converted to a number
    };
  };
}
```
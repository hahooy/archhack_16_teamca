#!/usr/local/bin/wolframscript
(* ::Package:: *)

segmentCornealUlcer[image_]:= Block[
	{r, g, b, greenParts},
	{r, g, b} = ColorSeparate[image, #] &/@ {"R", "G", "B"};
	greenParts = ImageMultiply[ColorNegate[Binarize[b, 0.9]],Binarize[g, 0.4], ColorNegate[Binarize[r, 0.8]]];
	Opening[SelectComponents[greenParts, Large], 2]
]


imageName = $ScriptCommandLine[[2]];
img = Import[imageName];
{w, h} = ImageDimensions[img];
p = ImageResize[img, {w/10., h/10.}];
segment = segmentCornealUlcer[p];
mask = ColorReplace[segment, { White -> RGBColor[1,0,0,0.5], Black->Transparent}];
outputImage = ImageCompose[p, mask];
Export[StringDrop[imageName, -4] <> "_out.jpg", outputImage];

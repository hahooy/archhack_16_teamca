//
//  GraphViewController.swift
//  CataractTracker
//
//  Created by Junyuan Suo on 11/6/16.
//  Copyright © 2016 JYLock. All rights reserved.
//

import UIKit
import Charts

class GraphViewController: UIViewController, ChartViewDelegate {
    
    @IBOutlet weak var lineChartView: LineChartView!
    
    var moments = [Moment]()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        lineChartView.delegate = self
    }
    
    override func viewWillAppear(animated: Bool) {
        setChart()
    }
    
    func setChart() {
        lineChartView.noDataText = "You need to provide data for the chart."
        lineChartView.descriptionText = "Your Recovery Progress"
        
        moments = SharingManager.sharedInstance.moments
        
        var dataEntries: [ChartDataEntry] = []
        var dataPoints: [String] = []
//        let calendar = NSCalendar.currentCalendar()
        
        
        //let components = calendar.components([.Day, .Month, .Year], fromDate: date)
        
        
        
        for i in 0..<moments.count {
            
            let dataEntry = ChartDataEntry(value: Double((moments[i].area)!), xIndex: i)
            dataEntries.append(dataEntry)
            dataPoints.append(moments[i].createtime!)
        }
        
        let lineChartDataSet = LineChartDataSet(yVals: dataEntries, label: "Area")
        let lineChartData = LineChartData(xVals: dataPoints, dataSet: lineChartDataSet)
        lineChartView.data = lineChartData
        
        
//        lineChartView.xAxis.labelPosition = .Bottom
        
//        lineChartView.backgroundColor = UIColor(red: 189/255, green: 195/255, blue: 199/255, alpha: 1)
        lineChartView.animate(xAxisDuration: 2.0, yAxisDuration: 2.0)
        
    }

    
    func chartValueSelected(chartView: ChartViewBase, entry: ChartDataEntry, dataSetIndex: Int, highlight: ChartHighlight) {
        print("\(entry.value) in \(moments[entry.xIndex].createtime)")
    }
    
    

    /*
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destinationViewController.
        // Pass the selected object to the new view controller.
    }
    */

}

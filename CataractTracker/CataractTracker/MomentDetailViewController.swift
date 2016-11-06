//
//  MomentDetailViewController.swift
//  CataractTracker
//
//  Created by Junyuan Suo on 11/5/16.
//  Copyright © 2016 JYLock. All rights reserved.
//

import UIKit

class MomentDetailViewController: UIViewController {
    
    
    @IBOutlet weak var titleLabel: UILabel!
    @IBOutlet weak var createTimeLabel: UILabel!
    @IBOutlet weak var areaLabel: UILabel!
    @IBOutlet weak var descriptionLable: UILabel!
    @IBOutlet weak var imageView: UIImageView!
    
    var moment: Moment!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        self.navigationController!.toolbar.hidden = true
        // Do any additional setup after loading the view.
        titleLabel.text = moment.title
        createTimeLabel.text = moment.createtime
        areaLabel.text = String(moment.area!)
        descriptionLable.text = moment.description
        if let imageURL = moment.image_url {
            let url = NSURL(string: "\(SharingManager.Constant.baseServerURL)/\(imageURL)")
            if let data = NSData(contentsOfURL: url!) {
                let decodedImage = UIImage(data: data)
                imageView.image = decodedImage
            }
        }
        textStyle()
    }
    
    struct Constant {
        static let titleFont = UIFont(name: "HelveticaNeue-Medium", size: 18)
        static let timeFont = UIFont.boldSystemFontOfSize(12)
        static let areaFont = UIFont.boldSystemFontOfSize(12)
        static let descriptionFont = UIFont.preferredFontForTextStyle(UIFontTextStyleBody).fontWithSize(10)    }
    
    func textStyle() {
        titleLabel?.font =  Constant.titleFont
        titleLabel?.textColor = textColor
        titleLabel?.backgroundColor = backgroundColor
        
        areaLabel?.font = Constant.areaFont
        areaLabel?.textColor = textColor
        areaLabel?.backgroundColor = backgroundColor
        
        createTimeLabel?.font = Constant.timeFont
        createTimeLabel?.textColor = UIColor.grayColor()
        createTimeLabel?.backgroundColor = backgroundColor
        
        descriptionLable?.font = Constant.descriptionFont

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

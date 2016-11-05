//
//  FancyTextView.swift
//  CataractTracker
//
//  Created by Junyuan Suo on 11/5/16.
//  Copyright © 2016 JYLock. All rights reserved.
//

import UIKit

class FancyTextView: UITextView {
    
    override func awakeFromNib() {
        super.awakeFromNib()
        
        layer.borderColor = UIColor(red: SHADOW_GRAY, green: SHADOW_GRAY, blue: SHADOW_GRAY, alpha: 0.2).CGColor
        layer.borderWidth = 1.0
        layer.cornerRadius = 2.0
    }
    

    
}

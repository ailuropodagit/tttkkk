<div id="wrapper">
    <div id="search">
        <div id="search-content">
            <div id="search-content-box">
                <div id="search-content-box-content">
                    
                    <div id="search-box-block1">
                        
<style>
    #filtersubmit {
        position: absolute;
        left: 23px;
        top: 23px;
        color: #7B7B7B;
    }
</style>
                        
                        <input type="text" placeholder="Search: Tony Roma's, Vans, ChatTime">
                        <i id="filtersubmit" class="fa fa-search"></i>
                        
                    </div>
                    
                    <div id="search-box-block2">
                        <select>
                            <option>All</option>
                            <?php foreach ($state as $state_item): ?>
                                <option><?php echo $state_item['option_text'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    
                    <div id="search-box-block3">
                                                
                        <input type='submit' value='Search'>
                        
                    </div>
                    
                    <div id="float-fix"></div>
                    
                </div>
            </div>
        </div>
    </div>
</div>